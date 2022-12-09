<?php

namespace App\Http\Controllers\Seller;

use App\Events\ProductPriceUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashDeal;
use App\Service\FlashDealService;
use App\Service\FilterProductsService;
use App\Service\Media;

class FlashDealsController extends Controller
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
        $records = FlashDeal::whereStoreId($user->store_id)->with('product')->latest()->get();

        return view('seller.flash-deals.index', [
            'title' => __('Flash Deals'),
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

        return view('seller.flash-deals.add', [
            'title' => __('Add Flash Deal'),
            'section_title' => __('Flash Deals'),
            'products' => $products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param FlashDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function edit(FlashDeal $deal)
    {
        // authorize user
        $this->authorize('canView', $deal);

        $user = auth()->user();
        $filterProductsService = new FilterProductsService();
        $products = $filterProductsService->byStore($user->store_id)->sortBy(['title' => 'asc'])->get();

        return view('seller.flash-deals.edit', [
            'title' => __('Edit Flash Deal'),
            'section_title' => __('Flash Deals'),
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
        $flashDealService = new FlashDealService();
        $flashDealService->create($request->toArray(), $user);

        return redirect()->route('seller.flash.deals')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param FlashDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FlashDeal $deal)
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

        $flashDealService = new FlashDealService();
        $flashDealService->update($deal, $request->toArray());

        return redirect()->route('seller.flash.deals')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FlashDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function delete(FlashDeal $deal)
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
        $deal = FlashDeal::findOrFail($request->id);
        $deal->status = $request->value;
        $deal->save();

        // dispatch events
        ProductPriceUpdate::dispatch($deal->product);
    }
}
