<?php

namespace App\Http\Controllers;

use App\Models\RequestProduct;
use Illuminate\Http\Request;
use App\Extensions\Response;

class RequestProductController extends Controller
{
    
    public function index()
    {
        return view('web.request-product.index');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $requestProduct = new RequestProduct();
            $requestProduct->name = $request->name;
            $requestProduct->email = $request->email;
            $requestProduct->phone = $request->phone;            
            $requestProduct->link = $request->link;
            
            if ($request->file('image')) {
                $name = $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->store('images');
                $requestProduct->image = $path;
            }
            
            $requestProduct->store_name = $request->store_name;
            $requestProduct->product_type = $request->type;
            $requestProduct->prod_name = $request->prod_name;
//            $requestProduct->quantity = $request->quantity;
            $requestProduct->prod_desc = $request->prod_desc;
//            $requestProduct->comment = $request->comment;
            $requestProduct->status = true;
            $requestProduct->save();
            
            return response()->json([
                'message'   => 'Your Request send Successfully',
//                'uploaded_image' => '<img src="/images/'.$name.'" alt="" class="img-thumbnail" width="300" />',
                'class_name'  => 'alert-success'
               ]);

        } catch (\Exception $exc) {
            return response()->json([
            'message'   => __($exc->getMessage()),
            'uploaded_image' => '',
            'class_name'  => 'alert-danger'
           ]);
        }
    }

}
