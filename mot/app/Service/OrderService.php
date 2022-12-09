<?php

namespace App\Service;

use App\Events\OrderDelivered;
use App\Events\OrderStatusChange;
use App\Events\StoreOrderStatusChange;
use App\Models\CancelRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnOrderItems;
use App\Models\ReturnRequest;
use App\Models\Store;
use App\Models\StoreOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Monolog\Logger;
use phpDocumentor\Reflection\Types\Collection;
use App\Models\ReturnRequestGallery;
use App\Service\CountryService;
use Illuminate\Support\Facades\Mail;
use App\Jobs\RefundStoreOrder;
use App\Service\DhlService;
use App\Service\ShipmentRateService;
use Illuminate\Support\Str;
use Session;
use App\Models\Product;
use App\Models\UserDevices;
use App\Service\NotificationService;

class OrderService {

    protected $logger;

    /**
     * OrderService constructor.
     */
    public function __construct() {
        $this->logger = getLogger('order-service');
    }

    /**
     * Convert a cart to an order
     * Success :
     * 1. At the end of this method, cart will be emptied
     * 2. An order is created in Confirmed state.
     * 3. Products are deducted from stock
     * Failure:
     * 1. If there is not enough stock: an exception is thrown with appropriate message.
     * 2. Cart is intact with current values
     * 3. No stock is deducted.
     *
     * @param Cart $cart
     * @param CustomerAddress $address
     * @return Order
     * @throws \Exception
     */
    public function createOrder($cartService, Cart $cart, CustomerAddress $address, $paymentMethod = '', $currency_id = ''): Order {
        if (!$address && !$cart->address_id) {
            throw new \Exception(__('Unable to convert a cart to order without an address.'));
        }
        if ($cart->cart_products->count() == 0) {
            throw new \Exception(__('Unable to convert an empty cart to order.'));
        }
        $this->logger->debug('Creating order');
        $address_id = $this->getAddressId($cart, $address);

        $countryService = new CountryService();
        $countryId = $countryService->getCountryByCode('TR')->id;
        $getCurrency = getCurrencyById($currency_id);
        $tryCurrency = Currency::where('code', 'TRY')->first();

        $order = $this->_createOrder($address_id, $address, $cart, $paymentMethod, $countryId);
        $this->logger->debug('Created order', $order->toArray());

        $deliveryFee = 0;
        $volumetricWeight = 0;
        $weightTotal = 0;
        $deliveryRates = 0;

        foreach ($cart->getStores() as $storeId => $products) {

            $this->logger->debug('Checking Stock ', ['store' => $storeId]);
            $unstock = [];
            foreach ($products as $product) {
                if ($product->product->stock <= 0) {
                    $unstock[] = $product->product->title;
                }
            }
            $productsString = implode(", </br>", $unstock);
            if (count($unstock) > 0) {
                throw new \Exception(__('Not enough stock for ') . $productsString);
            }

            $proWeight = 0;
            $pro_weight = 0;
            foreach ($products as $product) {

                $width = $product->product->width * $product->quantity;
                $height = $product->product->height * $product->quantity;
                $length = $product->product->length * $product->quantity;
                $volumetricWeight = $width * $height * $length / 5000;
                $weightTotal = $product->product->weight;

                $proWeight = $volumetricWeight <=> $weightTotal;
                if ($proWeight == 0) {
                    $pro_weight = $volumetricWeight;
                }
                if ($proWeight < 0) {
                    $pro_weight = $weightTotal;
                }
                if ($proWeight > 0) {
                    $pro_weight = $volumetricWeight;
                }
                if ($pro_weight == 0) {
                    $pro_weight = 0.2 * $product->quantity;
                }

                $deliveryFee += $pro_weight;
            }

            $this->logger->debug('Getting Rates From DHL ', ['store' => $storeId]);
//            $dhlService = new DhlService();
//            $deliveryRates = $dhlService->getRateRequestByStore($storeId, $products, $address, $cart, $order);


            $storeOrder = $this->createStoreOrder($order->id, $storeId, $countryId);
            $this->logger->debug('Created Store Order ', $storeOrder->toArray());

            /** @var CartProduct $product */
            foreach ($products as $product) {
                $orderItem = $product->toOrderItem($storeOrder);
            }


//            $deliveryRate = currencyInTRY((string) $deliveryRates->Currency,(float) $deliveryRates->Amount);
//            $this->logger->debug('Got Rates From DHL ' , ['rate'=> $deliveryRate]);
            $storeOrder->updateStoreDeliveryFee($deliveryFee)->save();
            $storeOrder->refresh()->updateSubTotal()->save();
            $storeOrder->order_number = $this->generateStoreOrderNumber($storeId, $countryId);
            $this->logger->debug('Generated Store Order Number ', [$storeOrder->order_number]);
            $storeOrder->status = StoreOrder::UNIITIATED_ID;
            $storeOrder->save();
        }

        $shipmentRateService = new ShipmentRateService();

        $deliveryRates = $shipmentRateService->getShipmentRate($deliveryFee, $address);
        $deliveryRate = currencyInTRY("USD", (float) get_option('shipping_flat_rate'));
        if(isset($deliveryRates->rate)){
            $deliveryRate = currencyInTRY("USD", (float) $deliveryRates->rate);
        }
        $order->currency_id = $getCurrency->id;
        $order->forex_update_datetime = Carbon::now()->toDateTimeString();
        $order->forex_rate = number_format($getCurrency->base_rate, 3);
        $order->base_forex_rate = number_format($tryCurrency->base_rate, 3);
        $order->delivery_fee = $deliveryRate;
        $order->status = Order::UNIITIATED_ID;
        $order->save();
//        $this->logger->debug('In Order Service Cart Service Delivery Fee Prior to Update' , [$cartService->getDeliveryFee()]);
//        $this->logger->debug('In Order Service Cart Delivery Fee Prior to Update' , [$cart->delivery_fee]);
        $cartService->updateCartDeliveryFee($deliveryRate);
//        $this->logger->debug('In Order Service Cart Service Delivery Fee after Update' , [$cartService->getDeliveryFee()]);
//        $this->logger->debug('In Order Service Cart Delivery Fee after Update' , [$cart->refresh()->delivery_fee]);

        return $order;
    }

    public function updateOrder($cartService, Cart $cart, CustomerAddress $address, $orderId, $currency_id = ''): Order {
        if (!$address && !$cart->address_id) {
            throw new \Exception(__('Unable to convert a cart to order without an address.'));
        }
        if ($cart->cart_products->count() == 0) {
            throw new \Exception(__('Unable to convert an empty cart to order.'));
        }

        $address_id = $this->getAddressId($cart, $address);
        $countryService = new CountryService();
        $countryId = $countryService->getCountryByCode('TR')->id;
        $getCurrency = getCurrencyById($currency_id);
        $tryCurrency = Currency::where('code', 'TRY')->first();
        $coupon = Coupon::find($cart->coupon_id);

        $order = Order::firstOrNew(['id' => $orderId]);
        $order->address_id = $address_id;
        $order->address = (string) $address;
        $order->sub_total = $cart->sub_total;
        $order->coupon_id = $cart->coupon_id;

        if ($coupon != null) {
            $order->discount = $coupon->discount;
            $order->discount_type = $coupon->type;
        }

        $deliveryFee = 0;
        $volumetricWeight = 0;
        $weightTotal = 0;
        $deliveryRates = 0;

        foreach ($cart->getStores() as $storeId => $products) {

            $inStock = [];
            foreach ($products as $product) {
                if ($product->product->stock <= 0) {
                    $inStock[] = Str::limit($product->product->title, 15);
                }
            }
            $productsString = implode(", ", $inStock);
            if (count($inStock) > 0) {
                throw new \Exception(__('Not enough stock for following product(s): ') . $productsString);
            }

            $proWeight = 0;
            $pro_weight = 0;
            foreach ($products as $product) {
                $width = $product->product->width * $product->quantity;
                $height = $product->product->height * $product->quantity;
                $length = $product->product->length * $product->quantity;
                $volumetricWeight = $width * $height * $length / 5000;
                $weightTotal = $product->product->weight;
                $proWeight = $volumetricWeight <=> $weightTotal;
                if ($proWeight == 0) {
                    $pro_weight = $volumetricWeight;
                }
                if ($proWeight < 0) {
                    $pro_weight = $weightTotal;
                }
                if ($proWeight > 0) {
                    $pro_weight = $volumetricWeight;
                }
                if ($pro_weight == 0) {
                    $pro_weight = 0.2 * $product->quantity;
                }
                $deliveryFee += $pro_weight;
            }

//            $dhlService = new DhlService();
//            $deliveryRates = $dhlService->getRateRequestByStore($storeId, $products, $address, $cart, $order);
//            $deliveryRate = currencyInTRY((string) $deliveryRates->Currency,(float) $deliveryRates->Amount);
//            $deliveryFee += $deliveryRate;

            $storeOrder = StoreOrder::firstOrNew(['order_id' => $order->id, 'store_id' => $storeId]);
//            StoreOrder::where('order_id', $order->id)->delete();
            if ($storeOrder->id == NULL) {
              
                $countryId = $countryService->getCountryByCode('TR')->id;

                $this->createStoreOrder($order->id, $storeId, $countryId);
                $this->logger->debug('Created Store Order ' . $storeOrder->id);
            }

            /** @var CartProduct $product */
            $orderItems = collect([]);
            foreach ($products as $product) {
                $orderItems->add($product->toOrderItem($storeOrder));
            }

            $storeOrder->updateStoreDeliveryFee($deliveryFee)->updateSubTotal();
            $storeOrder->removeOrderItemsExcept($orderItems);
            $storeOrder->status = StoreOrder::UNIITIATED_ID;
            $storeOrder->save();
        }

        $shipmentRateService = new ShipmentRateService();
        $deliveryRates = $shipmentRateService->getShipmentRate($deliveryFee, $address);
        $deliveryRate = currencyInTRY("USD", (float) get_option('shipping_flat_rate'));
        if (isset($deliveryRates->rate)) {
            $deliveryRate = currencyInTRY("USD", (float) $deliveryRates->rate);
        }

        $order->currency_id = $getCurrency->id;
        $order->forex_update_datetime = Carbon::now()->toDateTimeString();
        $order->forex_rate = number_format($getCurrency->base_rate, 3);
        $order->base_forex_rate = number_format($tryCurrency->base_rate, 3);
        $order->delivery_fee = $deliveryRate;
        $order->status = Order::UNIITIATED_ID;
        $order->save();

        $cartService->updateCartDeliveryFee($deliveryRate);

        return $order;
    }

    /**
     * @param Cart $cart
     * @param CustomerAddress|null $address
     * @return int|null
     */
    protected function getAddressId(Cart $cart, ?CustomerAddress $address) {
        if ($address) {
            $address_id = $address->id;
            return $address_id;
        }
        $address_id = $cart->address_id;

        return $address_id;
    }

    public function createOrderReturnRequest($data) {
        if (count($data['order_item_id']) == 0) {
            throw new \Exception(__('Unable to create an empty return request.'));
        }
        $returnRequest = $this->saveReturnRequest($data);
        return $returnRequest;
    }

    public function saveReturnRequest($data) {
        try {
            \DB::beginTransaction();
            $returnRequest = new ReturnRequest();
            $returnRequest->status = $data['status'];
            $returnRequest->store_order_id = $data['store_order_id'];
            $returnRequest->notes = $data['request_note'];
            $returnRequest->save();

            foreach ($data['order_item_id'] as $key => $value):
                $returnOrderItem = new ReturnOrderItems();
                $returnOrderItem->order_item_id = $data['order_item_id'][$key];
                $returnOrderItem->return_request_id = $returnRequest->id;
                $returnOrderItem->quantity = $data['quantity'][$key];
                $returnOrderItem->reason = $data['reason'][$key];
                $returnOrderItem->note = $data['note'][$key] ?? '';
                $returnOrderItem->save();

                $gallery_array = explode(",", $data['gallery'][$key]);
                if (count($gallery_array) == 0) {
                    throw new \Exception(__('Unable to create an empty return request.'));
                }
                foreach ($gallery_array as $image):
                    $returnGallery = new ReturnRequestGallery();
                    $returnGallery->return_order_item_id = $returnOrderItem->id;
                    $returnGallery->image = $image;
                    $returnGallery->save();
                endforeach;

            endforeach;
            \DB::commit();
            return $returnRequest;
        } catch (\Exception $exc) {
            \DB::rollBack();
            throw $exc;
        }
    }

    public function updateReturnRequest($data) {
        $orderReturnRequest = ReturnRequest::where('store_order_id', $data['store_order_id'])->first();
        $orderReturnRequest->tracking_id = $data['tracking_id'];
        $orderReturnRequest->company_name = $data['company_name'];

        $orderReturnRequest->save();
        return $orderReturnRequest;
    }

    /**
     * @param StoreOrder $storeOrder
     * @param int $order_id
     * @return Collection
     */

    /**
     * @param Customer $customer
     * @param int $store_order_id
     * @param $reason
     * @param string|null $notes
     * @return StoreOrder|Builder|Model|object|null
     * @throws \Throwable
     */
    public function createCancelOrderRequest(Customer $customer,array $orderItemIds, $reason, string $notes = null, $ip = null)
    {
//        $order = Order::whereId($order_id)->first();
//        $storeOrders = StoreOrder::whereOrderId($order_id)->get();
//
//        if ($order->customer_id !== $customer->id) {
//            throw new \Exception("Permission Denied.", 401);
//        }

        try {
            \DB::beginTransaction();
            foreach ($orderItemIds as $orderItemId) {
                $orderItem = OrderItem::find($orderItemId);
//                dd($orderItem);
                $orderItem->toCancelRequest($ip);
                $cancelRequest = new CancelRequest();
                $cancelRequest->store_order_id = $orderItem->store_order->id;
                $cancelRequest->order_item_id = $orderItem->id;
                $cancelRequest->reason = $reason;
                $cancelRequest->notes = $notes ?? '';
                $cancelRequest->save();
//                $this->updateStock($storeOrder->order_items);
            }
            \DB::commit();

        } catch (\Exception $exc) {
            \DB::rollBack();
            throw $exc;
        }
    }

    public function createCancelOrderAllRequest(Customer $customer, int $order_id, $reason, string $notes = null, $ip = null) {

        $order = Order::whereId($order_id)->first();
        $storeOrders = StoreOrder::whereOrderId($order_id)->get();

        if ($order->customer_id !== $customer->id) {
            throw new \Exception("Permission Denied.", 401);
        }

        try {

            \DB::beginTransaction();

            foreach ($storeOrders as $storeOrder) {
                $storeOrder->toCancelRequest($ip);
                $cancelRequest = new CancelRequest();
                $cancelRequest->store_order_id = $storeOrder->id;
                $cancelRequest->reason = $reason;
                $cancelRequest->notes = $notes ?? '';
                $cancelRequest->save();
                $this->updateStock($storeOrder->order_items);
            }

            \DB::commit();

            return $order;
        } catch (\Exception $exc) {
            $this->logger->error($exc->getMessage());
            \DB::rollBack();
            throw $exc;
        }
    }

    public function updateStock($orderItem) {

        foreach ($orderItem as $item) {
            $product = Product::find($item->product_id);
            $product->stock = $product->stock + $item->quantity;
            $product->save();
        }
    }

    /**
     * @param Order $order
     * @param Store $store
     * @return string
     */
    protected function generateStoreOrderNumber($storeId, $countryId) {
        return sprintf("%03d-%03d-%05d", $countryId, $storeId, StoreOrder::max('id') + 1 + rand(0, 9999));
    }

    /**
     * @param Order $order
     * @param Store $store
     * @return string
     */
    protected function generateOrderNumber($countryId) {
        return sprintf("%03d-%05d", $countryId, Order::max('id') + 1 + rand(0, 9999));
    }

//    public function getCustomersOrder(Customer $customer, int $order_id) {
//        return StoreOrder::query()
//                        ->whereHas('order', function (Builder $query) use ($customer, $order_id) {
//                            $query->where('customer_id', $customer->id)
//                            ->where('id', $order_id);
//                        })
//                        ->get();
//    }

    public function getCustomersOrder(Customer $customer, int $order_id) {
        return Order::query()->where('customer_id', $customer->id)
                        ->whereHas('store_orders', function (Builder $query) use ($order_id) {
                            $query->where('order_id', $order_id);
                        })->get();
    }

    /**
     * @param int $order_id
     * @return StoreOrder[]|Builder[]|\Illuminate\Database\Eloquent\Collection
     * @deprecated
     */
    public function getCustomerStoreOrder(int $order_id) {
        /**
         * Osama Khan, Please tell me why should I not consider the following
         * epitome of stupidity
         */
        return StoreOrder::query()
                        ->with('order.currency')
                        ->where('order_id', $order_id)
                        ->whereHas('order', function (Builder $query) use ($order_id) {
                            $query->where('id', $order_id);
                        })
                        ->get();
    }

    public function getCustomersOrderList(Customer $customer) {
        return StoreOrder::query()->with('order')
                        ->whereHas('order', function (Builder $query) use ($customer) {
                            $query->where('customer_id', $customer->id);
                        })->where('is_archived', StoreOrder::NOTARCHIVED)->orderBy('created_at', 'desc')
                        ->get();
    }

    public function getCustomersCancelledOrder(Customer $customer) {
        return $this->getCustomersOrderWithStatus($customer, Order::CANCELLED_ID);
    }

    public function getCustomersOrderWithStatus(Customer $customer, int $status) {
        return Order::query()
                        ->where('customer_id', $customer->id)
                        ->whereHas('store_orders', function (Builder $query) use ($status) {
                            $query->where('status', $status);
                        })
                        ->orderBy('order_date', 'desc')
                        ->get();
    }

    public function getCustomerCancelStoreOrder(int $order_id) {
        return CancelRequest::whereStoreOrderId($order_id)->first();
    }

    /**
     * upload gallery
     *
     * @param array $request
     * @return string|array
     */
    public function upload(array $request) {
        if (isset($request['file']) && !empty($request['file'])) {

            $gallery_response = [];
            foreach ($request['file'] as $file) {
                $imageName = Media::upload($file, true, false);
                $response['name'] = $imageName;
            }

            return $response;
        }
        return "error";
    }

    /**
     * delete gallery image
     *
     * @param array $request
     * @return string
     */
    public function delete(array $request): string {
        if (isset($request['filename']) && !empty($request['filename'])) {

            $filename = $request['filename'];
            Media::delete($filename);

            return "success";
        }
        return "error";
    }

    /**
     * change order state to archive
     *
     * @param id $id
     * @return row
     */
    public function saveArchiveOrder($id) {
        $storeOrder = StoreOrder::find($id);
        $storeOrder->is_archived = StoreOrder::ARCHIVED;
        $storeOrder->archived_date = Carbon::now();
        $storeOrder->save();

        return $storeOrder;
    }

    public function storeOrderStatus(StoreOrder $storeOrder, int $status, $user)
    {
        $storeOrder->order->status = $status;
        $storeOrder->order->save();

        $old_status = $storeOrder->status;
        $storeOrder->changeStatus($status);
        $storeOrder->save();

        // TODO we will need to work for translation
        $storeOrder->order_statusues()->create([
            'from_status' => $old_status,
            'to_status' => $status,
            'user_id' => $user->id
        ]);

        // approve iyzico payment
        /* if($status === StoreOrder::DELIVERED_ID) {
          OrderDelivered::dispatch($storeOrder);
          } */

        /* if admin or seller cancelled an order */
        if ($status == StoreOrder::CANCELLED_ID) {
            RefundStoreOrder::dispatch($storeOrder);
        }

        /* Resolved cancel order request if any. */
        $this->resolvedCancelRequest($status, $storeOrder);

        // Raise an event .. there may be other things we might do
        event(new StoreOrderStatusChange($storeOrder));

        return $storeOrder;
    }

    public function orderStatus(Order $order, int $status, $user)
    {
        $old_status = $order->status;
        $order->status = $status;
        $order->save();

        $store_orders = StoreOrder::query()
            ->where('order_id', $order->id)
            ->where('status', $old_status)
            ->with(['order_items' => function ($query) use ($old_status) {
                return $query->where(function ($query) use ($old_status) {
                    return $query->where('status', $old_status)->orWhere('status', 0);
                });
            }])
            ->get();

        foreach ($store_orders as $storeOrder) {

            $storeOrder->changeStatus($status);
            $storeOrder->save();

            // update order items status
            foreach ($storeOrder->order_items as $item) {
                $item->status = $status;
                $item->save();
            }

            // TODO we will need to work for translation
            $storeOrder->order_statusues()->create([
                'from_status' => $old_status,
                'to_status' => $status,
                'user_id' => $user->id
            ]);

            if ($status == Order::CANCELLED_ID) {
                RefundStoreOrder::dispatch($storeOrder);
            }

            /* Resolved cancel order request if any. */
            $this->resolvedCancelRequest($status, $storeOrder);
        }

        if (isset($order->customer_id) && $order->customer_id != null) {
            $customer_id = $order->customer_id;
            $userDevice = UserDevices::where('customer_id', $order->customer_id)->where('is_order_notifications', true)->latest()->first();
        }

        if (isset($userDevice->token)) {

            $title = _("Order Status");
            $description = __("Your Order Status is ".$status);
            $type = 'order';
            $lang_id = 1;
            $token = $userDevice->token;

            $message = [
                'title' => $title,
                'description' => $description,
                'customer_id' => $customer_id,
                'type' => $type,
                'language_id' => $lang_id,
                'token' => $token,
            ];
            $screenA = '/order/'.$order->id;
            $notificationService = new NotificationService();
            $notificationService->saveNotifications($message);
            $notificationService->sendNotification($token, $message, $screenA);
        }

        // TODO we will need to work for translation
        $order->order_statusues()->create([
            'from_status' => $old_status,
            'to_status' => $status,
            'user_id' => $user->id
        ]);

        event(new OrderStatusChange($order));

        return $order;
    }

    /**
     * @param $status
     * @param $storeOrder
     */
    private function resolvedCancelRequest($status, StoreOrder $storeOrder) {
        if ($status == StoreOrder::CANCELLED_ID || $status == StoreOrder::SHIPPED_ID) {
            $cancel_request = CancelRequest::where('store_order_id', $storeOrder->id)->first();
            if ($cancel_request) {
                $cancel_request->status = CancelRequest::RESOLVED;
                $cancel_request->save();
            }
        }
    }

    public function createStoreOrder($orderId, $storeId, $countryId) {
        $storeOrder = StoreOrder::create([
                    'status' => StoreOrder::UNIITIATED_ID,
                    'order_id' => $orderId,
                    'store_id' => $storeId
        ]);
        // TODO move this to StoreOrder Model
        $storeOrder->order_number = sprintf("%03d-%03d-%05d", $countryId, $storeId, $storeOrder->id + 1 + rand(0, 9999));
        return $storeOrder;
    }

    private function _createOrder($address_id, $address, $cart, $paymentMethod, $countryId) {
        $coupon = Coupon::find($cart->coupon_id);

        $orderData = [
            'status' => Order::UNIITIATED_ID,
            'customer_id' => $address->customer->id, // nullable so find a better way to pass address with null customer
            'address_id' => $address_id,
            'address' => (string) $address,
            'sub_total' => $cart->sub_total,
            'delivery_fee' => 0,
            'tax' => 0,
            'currency_id' => $cart->currency_id,
            'coupon_id' => $cart->coupon_id,
            'payment_type' => $paymentMethod,
        ];

        if ($coupon != null) {
            $orderData['discount'] = $coupon->discount;
            $orderData['discount_type'] = $coupon->type;
        }

        $order = Order::create($orderData);

        /** TODO move to Order Model */
        $order->order_number = sprintf("%03d-%05d", $countryId, $order->id + 1 + rand(0, 9999));

        return $order;
    }

    public function updateOrderForexRate($getCurrency) {
        $tryCurrency = Currency::where('code', 'TRY')->first();
        $orderId = Session::get('orderId');
        if ($orderId) {
            $order = Order::firstOrNew(['id' => $orderId]);
            $order->currency_id = $getCurrency->id;
            $order->forex_update_datetime = Carbon::now()->toDateTimeString();
            $order->forex_rate = number_format($getCurrency->base_rate, 3);
            $order->base_forex_rate = number_format($tryCurrency->base_rate, 3);
            $order->save();
        }
        return $this;
    }

}
