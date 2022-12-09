<?php

namespace App\Service\PaymentMethods;

interface PaymentGateway
{
    /**
     * authorize user
     *
     * @return array
     */
    public function authorize();

    /**
     * process payment
     *
     * @param int $order_id
     * @return array
     */
    public function processPayment(int $order_id);

    /**
     * verify payment
     *
     * @param mixed $paymentID
     * @param string $paymentType
     * @return array
     */
    public function verifyPayment($paymentID, $paymentType);

    /**
     * cancel payment
     *
     * @param mixed $paymentID
     * @return array
     */
    public function cancelPayment($paymentID);
}
