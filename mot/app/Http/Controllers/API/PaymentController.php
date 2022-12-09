<?php

namespace App\Http\Controllers\API;

use App\Events\OrderPlaced;
use App\Helpers\UtilityHelpers;
use App\Service\ApiCartService;
use App\Service\PaymentMethods\MyFatoorah;
use App\Service\TransactionService;
use App\Service\NotificationService;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Customer as CustomerResource;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\Order;
use App\Models\UserDevices;
use Auth;
use Session;
use App\Service\OrderService;
use App\Service\CustomerService;
use App\Service\CustomerAddressService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Service\MoTCartService;
use Monolog\Logger;
use App\Models\TransactionAttempt;
use App\Extensions\Response;


class PaymentController extends BaseController
{
    public function createOrder(Request $request)
    {
        $logger = getLogger('create-order');
        try {
            $logger->debug('In creating order : ');
            $cartService = new ApiCartService($request->session_id);
            $addressService = new CustomerAddressService();
            $orderService = new OrderService();
            $customerService = new CustomerService();
            $cart = $cartService->getCart();
            $currency_id = isset($request->currency_id) ? $request->currency_id : '';
            $orderId = $cart->order_id;

            /* check logged user and create guest user */
            $user = Auth('sanctum')->user();
            if ($user == null) {
                $customer = $customerService->createGuestCustomer($request->all());
                $logger->debug('Created Customer  : ' , $customer->toArray());
                $address = $addressService->createGuestAddress($request->all(), $customer);
                $logger->debug('Created Customer  : ' , $address->toArray());
            } else {
                $customer = Customer::findOrFail($user->id);
                if (isset($request->address_id)) {
                    $address = $addressService->getById($request->address_id);
                } else {
                    //$address = $addressService->getGuestAddress($customer);
                    $address = $addressService->updateGuestAddress($request->all(), $customer);
                }
            }

            if (isset($request['identity_number'])) {
                $customerService->addIdentityNumber($customer, $request->toArray());
            }
            $cartService->updateStatus(Cart::OPEN_ID);

            if ($orderId != null) {
                $logger->debug('Updating  Order : ' .  $orderId);
                $order = $orderService->updateOrder($cartService, $cart, $address, $orderId, $currency_id);
                if ($order) {
                    $data = [
                        'order' => $order,
                        'subTotal' => $cartService->getSubTotal(),
                        'discountedAmount' => $cartService->getDiscountedAmount(),
                        'deliveryFee' => $cartService->getDeliveryFee(),
                        'total' => $cartService->getTotal(),
                        'discount' => $cartService->getDiscountedAmount(),
                    ];
                    return $this->sendResponse($data, __('Order created successfully'));
                }
            } else {
                $logger->debug('There was no order we are creating one');
                $order = $orderService->createOrder($cartService, $cart, $address, Order::MYFATOORAH,$currency_id);
                $logger->debug('Created Order : ', $order->toArray());

                if ($order) {
                    $logger->debug('Set the session for orderId : ' . $order->id);
                    Session::put('orderId', $order->id);
                    $cartService->addOrderCart($order->id);
                    $data = [
                        'order' => $order,
                        'subTotal' => $cartService->getSubTotal(),
                        'discountedAmount' => $cartService->getDiscountedAmount(),
                        'deliveryFee' => $cartService->getDeliveryFee(),
                        'total' => $cartService->getTotal(),
                        'discount' => $cartService->getDiscountedAmount(),
                    ];
                    return $this->sendResponse($data, __('orders'));
                }
            }
            $cartService->updateStatus(Cart::OPEN_ID); //change
            return $this->sendResponse(Cart::OPEN_ID, __('Something went wrong'));
        } catch (\Exception $exception) {
            return $this->sendError(__('Error'), __($exception->getMessage()));
        }
    }

    public function placeOrder(Request $request)
    {
        $logger = getLogger('order-payment');
        try {
             $cartService = new ApiCartService($request->session_id);
            $orderId = $request->order_id;

            $order = Order::where('id', $orderId)->first();
            if ($order) {

                $cart = $cartService->getCart();
                $cartService->updateStatus(Cart::BEING_ORDER_ID); //change

                $paymentService = new MyFatoorah();
                $checkoutForm = $paymentService->processPayment($order->id);

                $data = ['checkoutForm' => $checkoutForm];
                return $this->sendResponse($data, __('Something went wrong'));
            } else {
                return;
            }
            
            $cartService->updateStatus(Cart::OPEN_ID); //change
            return $this->sendError(__('Sorry Something went wrong'), __($request));
        } catch (\Exception $exception) {
            return $this->sendError(__('Error'), __($exception->getMessage()));
        }
    }

    /*public function shipmentRate(Request $request)
    {
        $shipmentRateService = new ShipmentRateService();
        $deliveryRates = $shipmentRateService->getShipmentRate($weight, $address);

        return $this->sendResponse($deliveryRates, __('Item has been added to cart successfully'));
    }*/

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|void
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'session_id' => 'required',
            'payment_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }
        
        try {
            $order = Order::find($request->order_id);
            $paymentService = new MyFatoorah();
            $transactionService = new TransactionService();
            $transactionAttempt = $transactionService->createAttempt($order->id);

            $paymentStatus = $paymentService->verifyPayment($request->payment_id, Order::PAYMENTID, $transactionAttempt->id);
            if ($paymentStatus['success'] && strtolower($paymentStatus['data']['InvoiceStatus']) == "paid") {

                if ($order->status == Order::UNIITIATED_ID) {
                    $order->toConfirm();
                }

                $paymentService->orderSuccess($transactionAttempt->id, $paymentStatus);

                if (isset($request->session_id) && $request->session_id != '') {
                    $cartService = new ApiCartService($request->session_id);
                } else {
                    $cartService = $this->cartService();
                }

                $cartService->terminateCart(); //change order status
                UtilityHelpers::removeCartSessionId();
                Session::forget('orderId');

                foreach ($order->order_items as $item) {
                    $item->product->update(['stock' => DB::raw("`stock` - " . $item->quantity)]);
                    $item->product->refresh();
                }
                
                if (isset($order->customer_id) && $order->customer_id != null) {
                    $customer_id = $order->customer_id;
                    $userDevice = UserDevices::where('customer_id', $order->customer_id)->where('is_order_notifications',true)->latest()->first();
                } else {

                    if(isset($request['device_token'])){
                    $userDevice = UserDevices::where('token', $request['device_token'])->where('is_order_notifications',true)->latest()->first();
                     }
                }
                
                if(isset($userDevice->token)){

                    $title = _("Order Confirmation");
                    $description = __("Payment has been paid successfully");
                    $type = 'order' ;
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
                    $screenA = '/order/'.$request->order_id;
                    $notificationService = new NotificationService();
                    $notificationService->saveNotifications($message);
                    $notificationService->sendNotification($token, $message, $screenA);
                }

                // sending email
                event(new OrderPlaced($order));

                return $this->sendResponse($order, 'Payment has been paid successfully');
            }
            
            return $this->sendError(__('Payment failed'), []);
            
        } catch (\Exception $exc) {
            return $this->sendError(__('Payment failed'), $exc->getMessage());
        }
    }
    
    /**
     * @param $transactionAttemptId
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function myFatoorahCallback($transactionAttemptId, Request $request)
    {
        $logger = getLogger('myfatoorah-payment', Logger::DEBUG, 'logs/my-fatoorah-payments.log');
        $logger->debug('Retrieving payment status ', []);

        try {
            /** @var  $paymentService */
            $paymentService = new MyFatoorah();
            $paymentStatus = $paymentService->verifyPayment($request->paymentId, $keyType = Order::PAYMENTID,
                $transactionAttemptId);

            $logger->debug('payment status ', [$paymentStatus]);
            if($paymentStatus['success'] && strtolower($paymentStatus['data']['InvoiceStatus']) == "paid") {
                $order = $paymentService->orderSuccess($transactionAttemptId, $paymentStatus);
                Cart::where('order_id',$order->id)->update(['status'=> Cart::TERMINATED_ID]);
                
                return redirect()->route('thank-you', $order->id);
//                return $this->sendResponse($order->id, __('thank-you'));
            }
            $logger->debug('fatoorah TransactionStatus ', [$paymentStatus['data']['InvoiceTransactions'][0]['TransactionStatus']]);
          $errorrspose='';
          if (strtolower($paymentStatus['data']['InvoiceTransactions'][0]['TransactionStatus']) != 'succss') {
                $errorrspose = $paymentStatus['data']['InvoiceTransactions'][0]['Error'];
            }

        } catch (\Exception $exc) {
            return $this->sendError(__('Payment failed'), $exc->getMessage());
        }
        return redirect(route('cart',['error'=> $errorrspose]))->with('error', __($errorrspose));
//        return $this->sendError(__('Payment failed'), __($errorrspose));
    }

}
