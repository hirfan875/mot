<?php

namespace App\Http\Controllers\Seller;

use App\Events\ProductPriceUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyDeal;
use App\Service\DailyDealService;
use App\Service\FilterProductsService;
use App\Service\Media;

class DailyDealsController extends Controller
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
    public function index()
    {
        $user = auth()->user();
        $records = DailyDeal::whereStoreId($user->store_id)->with('product')->latest()->get();

        return view('seller.daily-deals.index', [
            'title' => __('Daily Deals'),
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $filterProductsService = new FilterProductsService();
        $products = $filterProductsService->byStore($user->store_id)->sortBy(['title' => 'asc'])->get();

        return view('seller.daily-deals.add', [
            'title' => __('Add Daily Deal'),
            'section_title' => __('Daily Deals'),
            'products' => $products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DailyDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyDeal $deal)
    {
        // authorize user
        $this->authorize('canView', $deal);

        $user = auth()->user();
        $filterProductsService = new FilterProductsService();
        $products = $filterProductsService->byStore($user->store_id)->sortBy(['title' => 'asc'])->get();

        return view('seller.daily-deals.edit', [
            'title' => __('Edit Daily Deal'),
            'section_title' => __('Daily Deals'),
            'row' => $deal,
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'discount' => 'required',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png'
        ]);

        $user = auth()->user();
        $dailyDealService = new DailyDealService();
        $dailyDealService->create($request->toArray(), $user);

        return redirect()->route('seller.daily.deals')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DailyDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DailyDeal $deal)
    {
        // authorize user
        $this->authorize('canUpdate', $deal);

        $request->validate([
            'product' => 'required',
            'discount' => 'required',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
            'image' => 'sometimes|mimes:jpg,jpeg,png'
        ]);

        $dailyDealService = new DailyDealService();
        $dailyDealService->update($deal, $request->toArray());

        return redirect()->route('seller.daily.deals')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DailyDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function delete(DailyDeal $deal)
    {
        // authorize user
        $this->authorize('canDelete', $deal);

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
}
