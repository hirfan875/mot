<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TransactionAttempt;
use App\Service\PaymentMethods\MyFatoorah;
use App\Service\PaymentMethods\IyzicoPayment;
use App\Service\TransactionService;
use Illuminate\Http\Request;
use App\Helpers\UtilityHelpers;
use App\Service\OrderService;
use App\Service\CustomerAddressService;
use App\Service\MoTCartService;
use App\Models\Cart;
use Session;
use App\Extensions\Response;
use App\Events\OrderPlaced;
use App\Service\CustomerService;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Monolog\Logger;
use DB;

class PaymentController extends Controller {

    /**
     * TODO probably not used .. please confirm and remove
     */
    public function failure(Request $request) {
//        dd('Failure: ', $request->toArray());
    }

    public function createOrder(Request $request)
    {
        $logger = getLogger('create-order');
        try {
            $logger->debug('In creating order : ');
            $cartService = new MoTCartService(UtilityHelpers::getCartSessionId());
            $addressService = new CustomerAddressService();
            $orderService = new OrderService();
            $customerService = new CustomerService();
            $cart = $cartService->getCart(UtilityHelpers::getCartSessionId());
            
            $orderId = ($cart->order_id) ? $cart->order_id : Session::get('orderId');
            $logger->debug('Got Order Session Id  : ' .  $orderId);

                /* check logged user and create guest user */
                $user = Auth::guard('customer')->user();
                
                if ($user == null) {
                    $customer = $customerService->createGuestCustomer($request->all());
                    $logger->debug('Created Customer  : ' , $customer->toArray());
                    $address = $addressService->createGuestAddress($request->all(), $customer);
                    $logger->debug('Created Customer  : ' , $address->toArray());
                } else {
                    $customer = Customer::findOrFail($user->id);
                    if(isset($request->address_id)){
                        $address = $addressService->getById($request->address_id);
                    } else {
                        //$address = $addressService->getGuestAddress($customer);
                        $address = $addressService->updateGuestAddress($request->all(), $customer);
                    }
                }
                
                if (isset($request['identity_number'])) {
                    $customerService->addIdentityNumber($customer, $request->toArray());
                }

                $cartService->updateStatus(Cart::OPEN_ID); //change
                
                if ($orderId) {
                    $logger->debug('Updating  Order : ' .  $orderId);
                    $order = $orderService->updateOrder($cartService, $cart, $address,$orderId);
                    if ($order) {
                        $data = [
                            'order' => $order,
                            'subTotal' => currency_format($cartService->getSubTotal()),
                            'discountedAmount' => currency_format($cartService->getDiscountedAmount()),
                            'deliveryFee' => ($cartService->getDeliveryFee() > 0) ? currency_format($cartService->getDeliveryFee()) : 'Free Shipping' ,
                            'total' => currency_format($cartService->getTotal()),
                            'discount' => $cartService->getDiscountedAmount(),
                        ];
                        $logger->debug('Updated  Order : ' .  $orderId , $order->toArray());
                        return Response::success(null, $data, $request);
                    }
                    // we should never land here .. Zahid Please restructure your code
                } else {
                    $logger->debug('There was no order we are creating one');

                    $order = $orderService->createOrder($cartService, $cart, $address, Order::MYFATOORAH);
                    $logger->debug('Created Order : ', $order->toArray());

                    if ($order) {
                        $logger->debug('Set the session for orderId : ' . $order->id);
                        
                        Session::put('orderId', $order->id);
                        $cartService->addOrderCart($order->id);
                        $data = [
                            'order' => $order,
                            'subTotal' => currency_format($cartService->getSubTotal()),
                            'discountedAmount' => currency_format($cartService->getDiscountedAmount()),
                            'deliveryFee' => currency_format($cartService->getDeliveryFee()),
                            'total' => currency_format($cartService->getTotal()),
                            'discount' => $cartService->getDiscountedAmount(),
                        ];
                           $logger->debug('Cart Delivery Fee Prior to Partial ' , [$cart->refresh()->delivery_fee]);
                        $logger->debug('Cart Service Delivery Fee Prior to Partial ' , [$cartService->getDeliveryFee()]);
                        return Response::success(null, $data, $request);

                    }
                }
            $cartService->updateStatus(Cart::OPEN_ID); //change
            return redirect()->back()->with('success', 'Something went wrong');
        } catch (\Exception $exception) {
            $logger->critical($exception->getMessage());
            throw $exception;
        }
    }

    public function placeOrder(Request $request)
    {
        $logger = getLogger('order-payment');
        try {
            $cartService = new MoTCartService(UtilityHelpers::getCartSessionId());
            $addressService = new CustomerAddressService();
            $customerService = new CustomerService();
            $cart = $cartService->getCart(UtilityHelpers::getCartSessionId());
            
            $orderId = ($cart->order_id) ? $cart->order_id : Session::get('orderId');

            /* check logged user and create guest user */
            $user = Auth::guard('customer')->user();
            if($user == null) {
                $customer = $customerService->createGuestCustomer($request->all());
                $address = $addressService->getGuestAddress($customer);
            } else {
                $customer = Customer::findOrFail($user->id);
                if(isset($request->address_id)){
                    $address = $addressService->getById($request->address_id);
                } else {
                    //$address = $addressService->getGuestAddress($customer);
                    $address = $addressService->updateGuestAddress($request->all(), $customer);
                }
            }

            if (!$orderId) {
                $logger->debug('No Order Id Found');
                throw new \Exception('Sorry No Order Found. This should never happen. We are looking at this Issue.');
            }
            
            $order = Order::where('id', $orderId)->first();
            if($order) {
                
                $cart = $cartService->getCart(UtilityHelpers::getCartSessionId());
                $cartService->updateStatus(Cart::BEING_ORDER_ID); //change

                $paymentService = new MyFatoorah();
                $checkoutForm = $paymentService->processPayment($order->id);

                $data = ['checkoutForm' => $checkoutForm];
                return Response::success(null, $data, $request);
            }
            $cartService->updateStatus(Cart::OPEN_ID); //change
            return Response::error('','Sorry Something went wrong',  $request, 400);
        } catch (\Exception $exception) {
            $logger->critical($exception->getMessage());
            throw $exception;
        }
    }


    /**
     * @param TransactionAttempt $transactionAttempt
     * @param Request $request
     */
    public function iyzicoCallback(TransactionAttempt $transactionAttempt, Request $request)
    {
        $logger = getLogger('iyzico-payment');
        $logger->debug('Retrieving payment status ', []);
        try {
            /** @var IyzicoPayment $paymentService */
            $paymentService = new IyzicoPayment();
            $paymentStatus = $paymentService->retrievePayment($transactionAttempt, $request->token);
            $logger->debug('payment status ', [$paymentStatus]);
            $transactionAttempt->transaction_response = $paymentStatus;
            $transactionAttempt->save();
            return redirect()->route('thank-you', $transactionAttempt->order->id);
        } catch (\Exception $exc) {
            $logger->critical(__($exc->getMessage()));
        }
        return redirect(route('home'));
    }

    public function thankYou(Order $order)
    {
        $cartService = new MoTCartService(UtilityHelpers::getCartSessionId());
        $cartService->terminateCart(); //change order status
        UtilityHelpers::removeCartSessionId();
        Session::forget('orderId');
        
         foreach ($order->order_items as $item) {
            $title = $item->product->title;
            if($item->product->isVariation()){
                $attributeNames = UtilityHelpers::getVariationNames($item->product);
                $title = $item->product->parent->title. ' '. implode(', ', $attributeNames);
            }

            $item->product->update(['stock' => DB::raw("`stock` - " . $item->quantity)]);
            $item->product->refresh();
        }
        
        // sending email
        event(new OrderPlaced($order));
        return view('customer.order-success', ['order' => $order]);
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
                return redirect()->route('thank-you', $order->id);
            }
            $logger->debug('fatoorah TransactionStatus ', [$paymentStatus['data']['InvoiceTransactions'][0]['TransactionStatus']]);
          $errorrspose='';
          if (strtolower($paymentStatus['data']['InvoiceTransactions'][0]['TransactionStatus']) != 'succss') {
                $errorrspose = $paymentStatus['data']['InvoiceTransactions'][0]['Error'];
            }

        } catch (\Exception $exc) {
            $logger->critical($exc->getMessage());
            throw $exc;
        }
        return redirect(route('cart'))->with('error', __($errorrspose));
    }
}
