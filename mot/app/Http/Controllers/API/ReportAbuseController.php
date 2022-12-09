<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserDevices;
use App\Models\Customer;
use App\Models\ReportAbuse;
use Illuminate\Http\Request;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportAbuseController extends BaseController
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
            'product_id' => 'required',
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

            $reportAbuse = new ReportAbuse();
            $reportAbuse->product_id = isset($input['product_id']) ? $input['product_id'] : null;
            $reportAbuse->customer_id = $customer_id;
            $reportAbuse->device_token = isset($input['device_token']) ? $input['device_token'] : null;
            $reportAbuse->title = isset($input['title']) ? $input['title'] : null;
            $reportAbuse->detail = isset($input['detail']) ? $input['detail'] : null;
            $reportAbuse->status = true;
            $reportAbuse->save();

        } catch (\Exception $exc) {
            return $this->sendError(__('Unable to submit form.'), $exc->getMessage());
        }
        return $this->sendResponse($reportAbuse, __('Your request has been sent successfully !'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReportAbuse  $reportAbuse
     * @return \Illuminate\Http\Response
     */
    public function show(ReportAbuse $reportAbuse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReportAbuse  $reportAbuse
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportAbuse $reportAbuse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReportAbuse  $reportAbuse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportAbuse $reportAbuse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReportAbuse  $reportAbuse
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportAbuse $reportAbuse)
    {
        //
    }
}
