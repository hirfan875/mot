<?php

namespace App\Http\Controllers\Customer;

use App\Extensions\Response;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\StoreOrder;
use App\Models\Wishlist;
use App\Service\FilterOrderService;
use App\Service\FilterStoreOrderService;
use App\Service\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;

class OrderController extends Controller
{
    public function index(Request  $request)
    {
        try {
            $customer = Customer::findOrFail(Auth()->user()->id);
            $orderService = new FilterOrderService();
            $orderService->byCustomer($customer->id);
            $orderService->byArchive(Order::NOTARCHIVED);

            if (isset($request->status) && $request->status[0] != null) {
                $orderService->byStatus($request->status);
            }
            if ($request->start_date != null && $request->end_date != null) {
                $orderService->byDate($request->start_date, $request->end_date);
            }
            // todo replace get to paginate
            $orders = $orderService->relations(['customer', 'currency', 'store_orders', 'order_items'])->latest()->get();
        } catch (\Exception $exc) {
            return Response::error('customer.order', __($exc->getMessage()), $exc, $request);
        }

        return Response::success('customer.order',
                [ 'customer' => $customer,
                    'orders' => $orders
                        ], $request);
    }

    public function storeReview(Request  $request)
    {
        try {
            $customer = Customer::findOrFail(Auth()->user()->id);
            $orderService = new FilterStoreOrderService();
            $orderService->byCustomer($customer->id);
            $orderService->byStore($request->id);
            $orderService->byArchive(StoreOrder::NOTARCHIVED);
            $orderService->orderStatus();
            // todo replace get to paginate
            $storeOrders = $orderService->relations(['order.customer', 'order.currency', 'order.store_orders', 'order_items'])->latest()->get();
        } catch (\Exception $exc) {
            return Response::error('customer.order', __($exc->getMessage()), $exc, $request);
        }
        return Response::success('customer.store-review', [
            'customer' => $customer,
            'storeOrders' => $storeOrders
        ], $request);
    }

    public function show(Order $storeOrder)
    {
        try {
            $customer = Customer::findOrFail(Auth()->user()->id);
            if ($storeOrder->customer_id !== $customer->id) {
                throw new \Exception("Permission Denied.", 401);
            }
            $returnRequests = $storeOrder->return_requests()->with('return_order_items')->get();
            $cancelRequests = $storeOrder->cancel_request()->get();
            $totalDeliveryDays = get_option('shipping_days');
            $today = \carbon\Carbon::now();
            if ($totalDeliveryDays == null) {
                $totalDeliveryDays = 0;
            }
            $totalDeliveryDays = (int)$totalDeliveryDays;
            $orderedDays = $today->diffInDays($storeOrder->order_date);
            $remainingDeliveryDays = $totalDeliveryDays - $orderedDays;
            $deliveryPercent = 0;
            if ($totalDeliveryDays > 0) {
                $deliveryPercent = ($totalDeliveryDays-$remainingDeliveryDays) * 100 / $totalDeliveryDays;
            }
        } catch (\Exception $exc) {
            return Response::redirect(route('order-history'), request(), ['message' => __($exc->getMessage())]);
        }

        return Response::success('customer.order-detail.order', [
            'customer' => $customer,
            'order' => $storeOrder->order,
            'storeOrder' => $storeOrder,
            'returnRequests' => $returnRequests,
            'cancelRequests' => $cancelRequests,
            'totalDeliveryDays' => $totalDeliveryDays,
            'remainingDeliveryDays' => $remainingDeliveryDays,
            'deliveryPercent' => $deliveryPercent
        ], request());
    }

    public function cancellation(Request  $request)
    {
        try {
            $customer = Customer::findOrFail(Auth()->user()->id);
            $orderService = new OrderService();
            $cancelledOrders = $orderService->getCustomersCancelledOrder($customer);

        } catch (\Exception $exc) {
            return Response::error('customer.cancellation', __($exc->getMessage()), $exc, $request);
        }
        return Response::success('customer.cancellation', [
            'customer' => $customer,
            'cancelledOrders' => $cancelledOrders,
        ], $request);
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function trackPackage(Order $order)
    {
        try {
            $customer = Customer::with('addresses')->findOrFail(Auth()->user()->id);
            if ($order->customer_id !== $customer->id) {
                throw new \Exception("Permission Denied.", 401);
            }
        } catch (\Exception $exc) {
            return Response::redirect(route('order-detail' ,$order->id ),request(), ['message' => __($exc->getMessage())]);
        }
        return Response::success('customer.order-detail.order-tracking', [
            'customer' => $customer,
            'order' => $order
        ], request());
    }


    public function orderReturnProcess(Request $request)
    {
       try {
            $orderItems = OrderItem::whereHas('store_order', function ($query) use ($request) {
                            $query->where('order_id', $request->id);
                        })->with('return_order_items' , 'store_order')->get();

            $customer = Customer::with('addresses')->findOrFail(Auth()->user()->id);
            $orderService = new OrderService();
            $storeOrder = $orderService->getCustomersOrder($customer, $request->id);
        } catch (\Exception $exc) {
            return Response::redirect(route('order-history'),$request, ['message' => __($exc->getMessage())]);
        }
        return Response::success('customer.order-detail.order-return-form', [
            'customer' => $customer,
            'storeOrder' => $storeOrder,
            'orderItems' => $orderItems
        ], $request);
    }


    public function orderReturnRequest(Request $request)
    {
        try{
            $orderService = new OrderService();
            $returnRequest  =   $orderService->createOrderReturnRequest($request->all());
        }   catch (\Exception $exc) {
            // TODO Irfan please check why are we absorbing error message
            return Response::redirect(route('order-return-request'),$request);
        }
        return Response::success(null, ['message'=> __('Your request has been submitted successfully')], $request);
    }


    public function updateReturnRequest(Request $request)
    {
        try{
            $orderService  = new OrderService();
            $returnRequest = $orderService->updateReturnRequest($request->all());
        }   catch(\Exception $exc) {
            return Response::redirect(route('update-order-return-request'),$request, ['message' => __($exc->getMessage())]);
        }
        return Response::redirect(route('order-detail' , $request->store_order_id), $request, ['message' => __('Your request has been updated successfully')]);
    }


    /**
     * upload media gallery
     *
     * @param Request $request
     * @return string returned string contains JSON
     */
    public function galleryUpload(Request $request): string
    {
        $orderService = new OrderService();
        $response = $orderService->upload($request->toArray());

        return response()->json($response);
    }

    /**
     * delete media gallery file
     *
     * @param Request $request
     * @return string returned string contains JSON
     */
    public function galleryDelete(Request $request): string
    {
        $orderService = new OrderService();
        $response = $orderService->delete($request->toArray());

        return response()->json($response);
    }


    public function orderCancelRequest(Order $order)
    {
        try {
            $customer = Customer::findOrFail(Auth()->user()->id);
            if ($order->customer_id !== $customer->id) {
                throw new \Exception("Permission Denied.", 401);
            }
        } catch (\Exception $exc) {
            return Response::redirect(route('order-history'),request(),['message'=> __($exc->getMessage())]);
        }
        return Response::success('customer.order-detail.order-cancel-request', [
            'customer' => $customer,
            'order' => $order
        ], request());
    }
    public function createCancelOrderRequest(Request $request)
    {
        try {
            $customer = Customer::findOrFail(Auth()->user()->id);
            $orderService = new OrderService();
            $order = $orderService->createCancelOrderAllRequest($customer, $request->order_id, $request->reason, $request->notes, $request->ip());
        } catch (\Exception $exc) {
            // TODO There is a problem with following param list. We need to ensure we are sending all errors, with correct param
            return Response::redirect(route('order-detail' ,$request->order_id ),$request,['message' => __($exc->getMessage())]);
//            return Response::error('customer.order-detail.order-cancel-request',$exc->getMessage(), $exc->getMessage() ,  $request);
        }
        return Response::redirect(route('order-history'),$request)->with('success', __('Your request has been sent successfully!'));
    }

    public function createArchiveOrder(Request $request)
    {
        $orderService = new OrderService();
        $archivedOrder = $orderService->saveArchiveOrder($request->order_id);
        if($archivedOrder){
            return redirect()->route('order-history')->with('success', trans('order-success.order_archived_successfully')); // we should strive to do this , i-e to use a key that we can later use as a constant
        }
        return redirect()->back()->with('error', trans('site.failed_action'));
    }
}
