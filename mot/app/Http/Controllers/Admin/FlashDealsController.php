<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductPriceUpdate;
use App\Http\Controllers\Controller;
use App\Models\FlashDeal;
use App\Service\Media;
use Illuminate\Http\Request;
use App\Service\FilterProductsService;
use App\Service\FlashDealService;

class FlashDealsController extends Controller
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
        $records = FlashDeal::with(['product', 'store'])->orderBy('sort_order','asc')->get();

        return view('admin.flash-deals.index', [
            'title' => __('Flash Deals'),
            'records' => $records
        ]);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param FlashDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, FlashDeal $deal)
    {
        
        $filterProductsService = new FilterProductsService();
        $products = $filterProductsService->byStore($request->store)->sortBy(['title' => 'asc'])->get();

        return view('admin.flash-deals.edit', [
            'title' => __('Edit Flash Deal'),
            'section_title' => __('Flash Deals'),
            'row' => $deal,
            'store' => $request->store,
            'products' => $products
        ]);
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
      
        return redirect()->route('admin.flash.deals')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FlashDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function delete(FlashDeal $deal)
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
        $deal = FlashDeal::findOrFail($request->id);
        $deal->status = $request->value;
        $deal->save();

        // dispatch events
        ProductPriceUpdate::dispatch($deal->product);
    }

    /**
     * approve deal
     *
     * @param FlashDeal $deal
     * @return \Illuminate\Http\Response
     */
    public function approve(FlashDeal $deal)
    {
        $deal->is_approved = true;
        $deal->save();

        // dispatch events
        ProductPriceUpdate::dispatch($deal->product);

        return back()->with('success', __('Deal approved successfully.'));
    }
    /**
     * Show list for changing sort order
     *
     * @return \Illuminate\Http\Response
     */
    public function sorting()
    {
        $records = FlashDeal::with(['product', 'store'])->orderBy('sort_order','asc')->get();

        return view('admin.flash-deals.sorting', [
            'title' => __('Flash Deals'),
            'records' => $records
        ]);
    }

    /**
     * Update sorting order
     *
     * @param Request $request
     * @return void
     */
    public function updateSorting(Request $request)
    {
        foreach ( $request['items'] as $k=>$r ) {
            FlashDeal::where('id', $r['id'])->update(['sort_order' => $r['order'] + 1]);
        }
    }
}
