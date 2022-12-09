<?php


namespace Tests\Unit\Service;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\OrderItemTransaction;
use App\Models\OrderTransaction;
use App\Models\Product;
use App\Models\Store;
use App\Models\TransactionAttempt;
use App\Service\MoTCartService;
use App\Service\PaymentMethods\IyzicoPayment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IyzicoPaymentTests extends TestCase
{
    use DatabaseTransactions;


    public function testTransactionLog()
    {
        $service = new IyzicoPayment();
        $transactionAttempt = new TransactionAttempt();
        $transactionAttempt->transaction_response = file_get_contents('../docs/iyzico-responses/iyzico-multiple-store-payment.json');
        $transactionAttempt->save();
        $service->createTransactionLogs($transactionAttempt);

        $this->assertEquals(1, OrderTransaction::count());
        $this->assertEquals(2, OrderItemTransaction::count());
    }


}
