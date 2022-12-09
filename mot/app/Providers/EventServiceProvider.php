<?php

namespace App\Providers;

use App\Events\ProductKeywordUpdate;
use App\Events\ProductPriceUpdate;
use App\Listeners\UpdatePriceTable;
use App\Listeners\UpdateMetaKeyword;
use App\Events\SellerRegistered;
use App\Listeners\SendSellerEmailVerificationNotification;
use App\Events\OrderStatusChange;
use App\Listeners\SendOrderStatusChangeNotification;
use App\Events\OrderPlaced;
use App\Listeners\SendOrderPlacedNotification;
use App\Events\SellerApproval;
use App\Listeners\SendSellerApprovalNotification;
use App\Events\SetupIyzicoSubMerchant;
use App\Listeners\RegisterIyzicoSubMerchant;
use App\Events\RefundOrder;
use App\Listeners\ReceivedItemAsExpected;
use App\Events\OrderDelivered;
use App\Listeners\IyzicoMerchantPaymentApproval;
use App\Events\SellerReject;
use App\Events\StoreOrderStatusChange;
use App\Listeners\SendSellerRejectNotification;
use App\Listeners\SendStoreOrderStatusChangeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProductPriceUpdate::class => [
            UpdatePriceTable::class,
        ],
        ProductKeywordUpdate::class => [
            UpdateMetaKeyWord::class,
        ],
        SellerRegistered::class => [
            SendSellerEmailVerificationNotification::class,
        ],
        OrderStatusChange::class => [
            SendOrderStatusChangeNotification::class,
        ],
        StoreOrderStatusChange::class => [
            SendStoreOrderStatusChangeNotification::class,
        ],
        OrderPlaced::class => [
            SendOrderPlacedNotification::class,
        ],
        SellerApproval::class => [
            SendSellerApprovalNotification::class,
        ],
        SetupIyzicoSubMerchant::class => [
            RegisterIyzicoSubMerchant::class,
        ],
        RefundOrder::class => [
            ReceivedItemAsExpected::class,
        ],
        OrderDelivered::class => [
        //    IyzicoMerchantPaymentApproval::class,
        ],
        SellerReject::class => [
            SendSellerRejectNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
