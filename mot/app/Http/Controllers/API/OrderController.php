<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrdersResource;
use App\Service\FilterOrderService;
use Illuminate\Http\Request;
use App\Service\FilterStoreOrderService;
use App\Service\OrderService;
use App\Models\Customer;
use App\Models\Order;
use App\Models\StoreOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\UserDevices;
use App\Service\NotificationService;

class OrderController extends BaseController
{

    /**
     * This APi is related to store order table
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $ordersData = [];
            $customer = Customer::findOrFail(Auth()->user()->id);
            $orderService = new FilterStoreOrderService();
            $orderService->byCustomer($customer->id);
            $orderService->byArchive(StoreOrder::NOTARCHIVED);
            $baseStoreOrders = $orderService->relations(['order.customer', 'order.currency', 'order.store_orders', 'order_items']);
            if($request->store_id != null) {
                $baseStoreOrders = $orderService->byStore([$request->store_id]);
            }
            $storeOrders = $baseStoreOrders->latest()->get();

            $ordersData['paid'] = OrderResource::collection($storeOrders->where('status', StoreOrder::PAID_ID));
            $ordersData['ready_to_shipped'] = OrderResource::collection($storeOrders->where('status', StoreOrder::READY_ID));
            $ordersData['shipped'] = OrderResource::collection($storeOrders->where('status', StoreOrder::SHIPPED_ID));
            $ordersData['delivered'] = OrderResource::collection($storeOrders->where('status', StoreOrder::DELIVERED_ID));
            $ordersData['cancellation_requested'] = OrderResource::collection($storeOrders->where('status', StoreOrder::CANCEL_REQUESTED_ID));
            $ordersData['cancelled'] = OrderResource::collection($storeOrders->where('status', StoreOrder::CANCELLED_ID));
            $ordersData['returned'] = OrderResource::collection($storeOrders->where('status', StoreOrder::RETURN_ACCEPTED_ID));
        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }

        return $this->sendResponse($ordersData, 'Data loaded successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function createCancelOrderRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_items' => 'required|array',
            'reason' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }
        if (!Auth('sanctum')->check()) {
            return $this->sendError(__('User not found'), []);
        }
        try {
            $customer = Customer::findOrFail(Auth()->user()->id);
            $orderService = new OrderService();
            $order = $orderService->createCancelOrderRequest($customer, $request->order_items, $request->reason, $request->notes, $request->ip());

            if (isset($customer->id) && $customer->id != null) {
                $customer_id = $customer->id;
                $userDevice = UserDevices::where('customer_id', $customer->id)->where('is_order_notifications',true)->latest()->first();
            }
            if (isset($userDevice->token)) {

                $title = _("Order Cancellation");
                $description = __("Your Order has been Cancel successfully");
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
                $notificationService->sendNotification($token, $message,$screenA);
            }
        } catch (\Exception $exc) {
            return $this->sendError(__($exc->getMessage()));
        }

        return $this->sendResponse($order, __('Your request has been sent successfully!'));
    }

    /*public function orderCancelRequest(StoreOrder $storeOrder)
    {
        try {
            $customer = Customer::findOrFail(Auth()->user()->id);
            if ($storeOrder->order->customer_id !== $customer->id) {
                throw new \Exception("Permission Denied.", 401);
            }
        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }

        return $this->sendResponse($storeOrder, __('Your request has been sent successfully!'));

    }*/

    public function orderReturnRequest(Request $request)
    {
        try{
            $orderService = new OrderService();
            $returnRequest  =   $orderService->createOrderReturnRequest($request->all());
        }   catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }
        return $this->sendResponse($returnRequest, __('Your request has been submitted successfully!'));
    }

    public function updateReturnRequest(Request $request)
    {
        try{
            $orderService  = new OrderService();
            $returnRequest = $orderService->updateReturnRequest($request->all());
        }   catch(\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }
        return $this->sendResponse($returnRequest, __('Your request has been updated successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getOrderDetail(Request $request)
    {
        try {
            /*logged in user order detail*/
            if (isset($request->id) && $request->id != null) {
                $order = Order::find($request->id);
            } else {
                /*for guest user track order*/
                $order = Order::where('order_number', $request->order_no)->orWhereHas('store_orders', function ($query) use ($request) {
                    return $query->where('order_number', $request->order_no);
                })->first();
            }

            if (is_null($order)) {
                return $this->sendError('No order found');
            }

            $orderData = [];
            $orderData['id'] = $order->id;
            $orderData['currency'] = $order->currency != null ? $order->currency->code : null;
            $orderData['order_number'] = $order->order_number;
            $orderData['base_forex_rate'] = $order->base_forex_rate;
            $orderData['forex_rate'] = $order->forex_rate;
            $orderData['status'] = $order->getStatus();
            $orderData['status_key'] = (int)$order->status;
            $orderData['sub_total'] = $order->sub_total;
            $orderData['delivery_fee'] = $order->delivery_fee;
            $orderData['discount'] = $order->getDiscount();
            $orderData['total'] = $order->total;
            $orderData['payment_method'] = $order->payment_type;
            $orderData['order_date'] = getFormatedDate($order->created_at);
            $orderData['order_address'] =  $order->address;
            $orderData['address_name'] = $order->customerAddresses != null ? $order->customerAddresses->name : null;
            $orderData['address'] = $order->customerAddresses != null ? $order->customerAddresses->address : null;
            $orderData['zipcode'] = $order->customerAddresses != null ? $order->customerAddresses->zipcode : null;
            $orderData['block'] = $order->customerAddresses != null ? $order->customerAddresses->block : null;
            $orderData['country'] = isset($order->customerAddresses->countries) ? $order->customerAddresses->countries->title : null;
            $orderData['state'] = isset($order->customerAddresses->states) ? $order->customerAddresses->states->title : null;
            $orderData['city'] = isset($order->customerAddresses->cities) ? $order->customerAddresses->cities->title : null;
            $orderData['phone'] = isset($order->customer->phone) ? $order->customer->phone : null;
            $orderData['items'] = OrderItemResource::collection($order->order_items);
            $orderData['status_buttons'] = $order->getPossibleStatusButton();
            $orderData['total_shipping_days'] = get_option('shipping_days');
            /*$returnRequests = $storeOrder->return_requests()->with('return_order_items')->get();
            $cancelRequests = $storeOrder->cancel_request()->get();
            $orderData['return_requests'] = $returnRequests;
            $orderData['cancel_requests'] = $cancelRequests;*/

        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }

        return $this->sendResponse($orderData, 'Data loaded successfully');
    }

    /**
     * This API relate to order table
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function ordersList(Request $request)
    {
        if (!Auth('sanctum')->check()) {
            return $this->sendError(__('User not found'), []);
        }

        try {
            $ordersData = [];
            $customer = Customer::findOrFail(Auth()->user()->id);
            $orderService = new FilterOrderService();
            $orderService->byCustomer($customer->id);

            $baseStoreOrders = $orderService->relations(['customer', 'currency', 'store_orders', 'order_items']);
            if ($request->store_id != null) {
                $baseStoreOrders = $orderService->byStore([$request->store_id]);
            }
            $storeOrders = $baseStoreOrders->latest()->get();

            $cancellationRequested = $orderService->cancellationRequested()->latest()->get();
            $returnedOrders = $orderService->returned()->latest()->get();

            $ordersData['paid'] = OrdersResource::collection($storeOrders->where('status', StoreOrder::PAID_ID));
            $ordersData['ready_to_shipped'] = OrdersResource::collection($storeOrders->where('status', StoreOrder::READY_ID));
            $ordersData['shipped'] = OrdersResource::collection($storeOrders->where('status', StoreOrder::SHIPPED_ID));
            $ordersData['delivered'] = OrdersResource::collection($storeOrders->where('status', StoreOrder::DELIVERED_ID));
            $ordersData['cancellation_requested'] = OrdersResource::collection($cancellationRequested);
            $ordersData['cancelled'] = OrdersResource::collection($storeOrders->where('status', StoreOrder::CANCELLED_ID));
            $ordersData['returned'] = OrdersResource::collection($returnedOrders);
        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }

        return $this->sendResponse($ordersData, 'Data loaded successfully');
    }
}
