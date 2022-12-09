<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserDevices;
use App\Models\Customer;
use App\Models\BlockStore;
use Illuminate\Http\Request;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlockStoreController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, NotificationService $notificationService)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }
        try {

            $input = $request->all();
            $customer_id = null;
            if (Auth('sanctum')->check()) {
                $customer_id = Auth('sanctum')->user()->id;
            }

            $blockStore = new BlockStore();
            $blockStore->store_id = isset($input['store_id']) ? $input['store_id'] : null;
            $blockStore->customer_id = $customer_id;
            $blockStore->device_token = isset($input['device_token']) ? $input['device_token'] : null;
            $blockStore->title = isset($input['title']) ? $input['title'] : null;
            $blockStore->detail = isset($input['detail']) ? $input['detail'] : null;
            $blockStore->status = true;
            $blockStore->save();

        } catch (\Exception $exc) {
            return $this->sendError(__('Unable to submit form.'), $exc->getMessage());
        }
        return $this->sendResponse($blockStore, __('Your request has been sent successfully !'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlockStore  $blockStore
     * @return \Illuminate\Http\Response
     */
    public function show(BlockStore $blockStore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlockStore  $blockStore
     * @return \Illuminate\Http\Response
     */
    public function edit(BlockStore $blockStore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlockStore  $blockStore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlockStore $blockStore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlockStore  $blockStore
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlockStore $blockStore)
    {
        //
    }
}
