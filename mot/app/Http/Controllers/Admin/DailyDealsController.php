<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductPriceUpdate;
use App\Http\Controllers\Controller;
use App\Models\DailyDeal;
use App\Service\Media;
use Illuminate\Http\Request;

class DailyDealsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = DailyDeal::with(['product', 'store'])->latest()->get();

        return view('admin.daily-deals.index', [
            'title' => __('Daily Deals'),
            'records' => $records
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DailyDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function delete(DailyDeal $deal)
    {
        Media::delete($deal->image);

        // dispatch events
        ProductPriceUpdate::dispatch($deal->product);

        $deal->delete();

        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * update status
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request)
    {
        $deal = DailyDeal::findOrFail($request->id);
        $deal->status = $request->value;
        $deal->save();

        // dispatch events
        ProductPriceUpdate::dispatch($deal->product);
    }

    /**
     * approve deal
     *
     * @param DailyDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function approve(DailyDeal $deal)
    {
        $deal->is_approved = true;
        $deal->save();

        // dispatch events
        ProductPriceUpdate::dispatch($deal->product);

        return back()->with('success', __('Deal approved successfully.'));
    }
}
