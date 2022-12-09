<?php

namespace App\Http\Controllers\Customer;

use App\Extensions\Response;
use App\Models\Customer;
use App\Service\ReviewService;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Http\Controllers\Controller;

class ProductReviewsController extends Controller
{
    public function index()
    {
        $reviewService = new ReviewService();
        $customer =  Customer::findOrFail(\Auth::user()->id);
        return $reviewService->customerProductReviews($customer);
    }

    public function show($id)
    {
        return ProductReview::find($id);
    }

    /**
     */
    public function store(Request $request)
    {
        $reviewService = new ReviewService();
        $customer =  Customer::findOrFail(\Auth::user()->id);
        $review = $reviewService->createProductReviewByCustomer($customer, $request->all());
        // TODO .. create a json only function
        // that returns 201
        return Response::success(null , $review->toArray(), $request);
    }

    //////////////////////////////////////////////
    /// routes not created for following methods
    //////////////////////////////////////////////
    public function update(Request $request, $id)
    {
        $article = ProductReview::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = ProductReview::findOrFail($id);
        $article->delete();

        return 204;
    }

    /**
     * upload media gallery
     *
     * @param Request $request
     * @return string returned string contains JSON
     */
    public function galleryUpload(Request $request): string
    {
        $reviewService = new ReviewService();
        $response = $reviewService->upload($request->toArray());

        return response()->json($response);
    }

    /**
     * delete media gallery file
     *
     * @param Request $request
     * @return string returned string contains JSON
     */
    public function galleryDelete(Request $request): string
    {
        $reviewService = new ReviewService();
        $response = $reviewService->delete($request->toArray());

        return response()->json($response);
    }
}
