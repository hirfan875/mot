<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Service\FilterReturnRequestService;
use Illuminate\Http\Request;
use App\Events\RefundOrder;

class ReturnRequestsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterReturnRequestService $filterReturnRequestService)
    {
        $user = auth()->guard('seller')->user();
        $records = $filterReturnRequestService->active()
            ->byStore($user->store_id)
            ->relations(['store_order.order.customer'])
            ->latest()
            ->get();

        return view('seller.return-requests.index', [
            'title' => __('Return Requests'),
            'records' => $records
        ]);
    }

    /**
     * Show the detail for specified resource.
     *
     * @param ReturnRequest $record
     * @return \Illuminate\Http\Response
     */
    public function detail(ReturnRequest $record)
    {
        $record->load(['store_order.order.customer', 'return_order_items.order_item.product']);

        return view('seller.return-requests.detail', [
            'title' => __('Detail'),
            'section_title' => __('Return Requests'),
            'row' => $record
        ]);
    }

    /**
     * Approve the specified resource from storage.
     *
     * @param ReturnRequest $record
     * @return \Illuminate\Http\Response
     */
    public function approve(ReturnRequest $record)
    {
        $record->status = ReturnRequest::APPROVED;
        $record->save();

        return back()->with('success', __('Request approved successfully.'));
    }

    /**
     * Reject the specified resource from storage.
     *
     * @param ReturnRequest $record
     * @return \Illuminate\Http\Response
     */
    public function reject(ReturnRequest $record)
    {
        $record->status = ReturnRequest::REJECTED;
        $record->save();

        return back()->with('success', __('Request rejected successfully.'));
    }

    /**
     * items received as expected
     *
     * @param ReturnRequest $record
     * @return \Illuminate\Http\Response
     */
    public function receivedExpected(ReturnRequest $record)
    {
        $record->received_expected = ReturnRequest::RECEIVED_EXPECTED;
        $record->save();
        RefundOrder::dispatch($record);

        return back()->with('success', __('Request status updated successfully.'));
    }

    /**
     * items received but not as expected
     *
     * @param ReturnRequest $record
     * @return \Illuminate\Http\Response
     */
    public function receivedNotExpected(ReturnRequest $record)
    {
        try {
            $record->received_expected = ReturnRequest::RECEIVED_NOTEXPECTED;
            $record->save();
            return back()->with('success', __('Request status updated successfully.'));

        } catch (\Throwable $e) {
            
            $record->received_expected = ReturnRequest::RECEIVED_PENDING;
            $record->save();
            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Archive the specified resource from storage.
     *
     * @param ReturnRequest $record
     * @return \Illuminate\Http\Response
     */
    public function archive(ReturnRequest $record)
    {
        $record->is_archive = true;
        $record->save();

        return back()->with('success', __('Request archived successfully.'));
    }
}
