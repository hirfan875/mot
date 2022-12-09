<?php

namespace App\Service;

use App\Models\Transaction;
use App\Models\TransactionAttempt;

class TransactionService
{
    /**
     * save transaction data
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data): Transaction
    {
        $transactoin = new Transaction();
        $transactoin->attempt_id = $data['CustomerReference'];
        $transactoin->transaction_id = $data['InvoiceReference'];
        $transactoin->data = json_encode($data);
        $transactoin->save();

        return $transactoin;
    }

    /**
     * create new transaction attempt
     *
     * @param int $order_id
     * @return TransactionAttempt
     */
    public function createAttempt(int $order_id): TransactionAttempt
    {
        $transcation = new TransactionAttempt();
        $transcation->order_id = $order_id;
        $transcation->save();

        return $transcation;
    }

    /**
     * update transaction attempt
     *
     * @param TransactionAttempt $transcation
     * @param array $response
     * @return void
     */
    public function updateAttempt(TransactionAttempt $transcation, array $response)
    {
        $transcation->transaction_response = json_encode($response);
        $transcation->save();
    }
}
