<?php

namespace App\Console\Commands;


use App\Models\TrackShipmentResponse;
use App\Models\ShipmentResponse;
use Illuminate\Console\Command;
use App\Models\StoreOrder;
use App\Models\Order;
use App\Service\DhlService;

class DhlStatusTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dhl:status-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'dhl status tracking';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $storeOrder = StoreOrder::with('shipment_reponse')->has('shipment_reponse')->whereIn('status', [StoreOrder::READY_ID, StoreOrder::SHIPPED_ID, StoreOrder::CANCEL_REQUESTED_ID, StoreOrder::RETURN_REQUESTED_ID])->get();
//        if($storeOrder){
//            foreach($storeOrder as $val){
//
//                $request = [
//                'message_time' => $val->shipment_reponse->message_time,
//                'message_reference' => $val->shipment_reponse->message_reference,
//                'number_awb' => $val->shipment_reponse->shipment_identification_number_awb,
//                'tracking_number' => $val->shipment_reponse->tracking_number,
//                ];
//                $dhlService = new DhlService;
//                $dhlService->getTrackShipmentRequest($request,$val);
//            }
//
//            $this->info('Command executed successfully.');
//            return 0;
//        }
    }
}
