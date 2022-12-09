<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\RefundOrder;
use App\Service\PaymentMethods\IyzicoPayment;
use Monolog\Logger;
use App\Service\PaymentMethods\MyFatoorah;

class ReceivedItemAsExpected
{
    public $queue = 'item-received-as-expected';

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RefundOrder $event)
    {
        $logger = getLogger('refund-order' , Logger::DEBUG, 'logs/refund.log');
        //get return request row
        $returnRequest = $event->returnRequest;
        $order = $returnRequest->store_order->order;

        /* check if order placed by myfatoorah otherwise using payu payment method */
        if($order->payment_type == $order::MYFATOORAH) {
            return $this->handleMyFatoorahRefund($returnRequest, $order);
        }

        try {
            $paymentService = new IyzicoPayment();
            //get iyzico payment
            $paymentDetails = $paymentService->getIyzicoPayment($returnRequest->store_order->order);
            if(strtolower($paymentDetails->getPaymentStatus()) !== 'success') {
                throw new \Exception(__('Cannot refund this order'));
            }
            $logger->debug('Received Payment Details');
            $logger->debug('returnRequest has Order Items ' . count($returnRequest->return_order_items));
            foreach ($returnRequest->return_order_items as $returnOrderItem) {
                $orderItemTransaction = $returnOrderItem->order_item_transaction;
                // get refunded order row
                $getRefundedOrderRow = $paymentService->getRefundedOrderByReturnOrderItem($returnOrderItem);
                /*check if payment is not already refunded */
                if($getRefundedOrderRow != null) {
                    $logger->debug('Already  Refunded For ' , $getRefundedOrderRow->toArray());
                    continue;
                    //TODO will handle using notes
                }
                $iyzicoTransaction = $this->getIyzicoTransactionByTransactionId($paymentDetails, $orderItemTransaction->paymentTransactionId);
                if($iyzicoTransaction == null) {
                    $logger->debug('Unable to get Iyzico Transaction For ' , [$orderItemTransaction->paymentTransactionId]);
                    continue;
                    //TODO will handle using notes
                }
                // TODO We should be able to make a single call to refund all order Items.
                $logger->debug('Sending Refund For ' , $returnOrderItem->toArray());
                $refundRequest = $paymentService->createAndSendRefundRequest($returnOrderItem);
            }
        } catch(\Exception $exc) {
            // TODO Add To Admin Error Logs
            throw  $exc;
        }
    }

    /**
     * get Iyzico Transaction Row
     *
     * @param  object  $paymentDetails
     * @param  string  $transactionId
     * @return object  $transactionRow
     */
    private function getIyzicoTransactionByTransactionId($paymentDetails, $transactionId)
    {
        $rawResult = json_decode($paymentDetails->getRawResult());
        $iyzicoTransactions = $rawResult->itemTransactions;

        $transactionRow = null;
        foreach ($iyzicoTransactions as $key => $value) {
            if($value->paymentTransactionId == $transactionId) {
                $transactionRow = $value;
                break;
            }
        }

        return $transactionRow;
    }

    /**
     * @param $returnRequest
     * @param $order
     */
    private function handleMyFatoorahRefund($returnRequest, $order)
    {
        $logger = getLogger('refund-order' , Logger::DEBUG, 'logs/refund.log');
        try{
            $paymentService = new MyFatoorah();
            $paymentDetails = $paymentService->getMyFatoorahPayment($returnRequest->store_order->order);
            if (!$paymentDetails['success'] && 'paid' !== strtolower($paymentDetails['data']['InvoiceStatus'])) {
                throw new \Exception(__('Cannot refund this order because payment not found'));
            }
            $logger->debug('Received Payment Details');
            $logger->debug('returnRequest has Order Items ' . count($returnRequest->return_order_items));
            foreach ($returnRequest->return_order_items as $returnOrderItem) {
                /*check if payment is not already refunded */
                $getRefundedOrderRow = $paymentService->getRefundedOrderByReturnOrderItem($returnOrderItem);
                /*check if payment is not already refunded */
                if($getRefundedOrderRow != null) {
                    $logger->debug('Already  Refunded For ' , $getRefundedOrderRow->toArray());
                    continue;
                    //TODO will handle using notes
                }
                $logger->debug('Sending Refund For ' , $returnOrderItem->toArray());
                $refundRequest = $paymentService->createAndSendRefundRequest($returnOrderItem);
            }

        } catch(\Exception $exc) {
            // TODO Add To Admin Error Logs
//            dd($exc);
            throw  $exc;
        }
    }
}
