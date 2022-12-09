<?php

namespace App\Http\Controllers;

use App\Models\RateRequest;
use Illuminate\Http\Request;
use App\Service\DhlService;

class RateRequestController extends Controller
{
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rateRequest(Request $request)
    {
       try {
            $dhlService = new DhlService;
            $response = $dhlService->getRateRequest($request->toArray());
            return response()->json(['response' => $response,'success' => true]);

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage(),'success' => false]);
        }
    }

    
}
