<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnOrderItems;
use App\Models\ReturnRequest;
use App\Models\StoreOrder;
use Illuminate\Database\Seeder;
use Request;

class ReturnRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Request $request)
    {
        $storeOrders = StoreOrder::with('order_items')->whereIn('status' , [StoreOrder::SHIPPED_ID, StoreOrder::DELIVERED_ID])->get();
        foreach ($storeOrders as $order) {
            $returnRequest = ReturnRequest::create([
                'status'            =>  '0',
                'store_order_id'    =>  $order->store_id,
                // 'quantity'          =>  $order->order_items[0]->quantity,
                // 'order_item_id'     =>  $order->order_items[0]->id,
                'notes'             =>  'dummy text',
            ]);

            foreach ($order->order_items as $item) {
                    ReturnOrderItems::create([
                            'order_item_id'            =>  $item->id,
                            'return_request_id'        =>  $returnRequest->id,
                            'quantity'                 =>  $item->quantity,
                            'reason'                   =>  'dummy text',
                            'note'                     =>  'dummy',           
                    ]);
            }
        }
    }
}