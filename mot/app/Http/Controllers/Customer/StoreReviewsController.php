<?php

namespace App\Http\Controllers\Customer;

use App\Models\Customer;
use App\Models\StoreReview;
use App\Service\ReviewService;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Http\Controllers\Controller;
use App\Service\StoreService;
use App\Extensions\Response;

class StoreReviewsController extends Controller
{
    public function index()
    {
        $reviewService = new ReviewService();
        $customer =  Customer::findOrFail(\Auth::user()->id);
        return $reviewService->customerStoreReviews($customer);
    }

    public function show($id)
    {
        return StoreReview::find($id);
    }

    public function store(Request $request)
    {
        $reviewService = new ReviewService();
        $customer =  Customer::findOrFail(\Auth::user()->id);
        return $reviewService->createStoreReviewByCustomer($customer, $request->all());
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

    public function storeReview(Request $request)
    {
        try {
        $storeService = new StoreService();
        $storeReview = $storeService->createFeedback($request->all());
        } catch (\Exception $exc) {
            return Response::redirect(route('store-review'), $request, ['message'=> __($exc->getMessage())]);
        }
        return redirect()->back()->with('success', __('Your feedback has been submitted successfully !'));
    }
}
