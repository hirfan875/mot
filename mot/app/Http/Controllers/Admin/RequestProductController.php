<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestProduct;
use Illuminate\Http\Request;
use App\Extensions\Response;
use App\Service\Media;

class RequestProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
//        $this->middleware('permission:request-product-list|request-product-detail|request-product-reply|request-product-delete', ['only' => ['index','detail']]);
//        $this->middleware('permission:request-product-reply', ['only' => ['reply']]);
//        $this->middleware('permission:request-product-delete', ['only' => ['delete']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = RequestProduct::latest()->get();

        return view('admin.request-product.index', [
            'title' => __('Request Product'),
            'records' => $records
        ]);
    }
    
    /**
     * Show the detail page for the specified resource.
     *
     * @param RequestProduct $requestProduct
     * @return \Illuminate\Http\Response
     */
    public function detail(RequestProduct $requestProduct)
    {
        
        $row = RequestProduct::where('id' , $requestProduct->id )->first();

        return view('admin.request-product.detail', [
            'title' => __('Request Product Detail'),
            'section_title' => __('Request Product'),
            'row' => $row,
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ContactInquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, ContactInquiry $inquiry)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);

        $contactInquiryService = new ContactInquiryService();
        $response = $contactInquiryService->sendReply($request->toArray(), $inquiry);

        return redirect()->route('admin.contact.inquiries')->with('success', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RequestProduct $requestProduct
     * @return \Illuminate\Http\Response
     */
    public function delete(RequestProduct $requestProduct)
    {
        $requestProduct->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }
    

}
