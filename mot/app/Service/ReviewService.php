<?php

namespace App\Service;


use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\ProductReviewGallery;
use App\Models\StoreOrder;
use App\Models\StoreReview;

class ReviewService
{
    protected $logger;

    /**
     * OrderService constructor.
     */
    public function __construct()
    {
        $this->logger = getLogger('reviews');
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function customerProductReviews(Customer $customer)
    {
        return ProductReview::query()
            ->where('customer_id', $customer->id)
            ->paginate();
    }

    /**
     * @param Customer $customer
     * @param array $validData
     * @return ProductReview
     * @throws \Exception
     */
    public function createProductReviewByCustomer(Customer $customer,array $validData)
    {
        if (!isset($validData['rating'])){
            throw new \Exception('Invalid rating data');
        }
        $orderItem = OrderItem::findOrFail($validData['order_item_id']);
        if ($orderItem->store_order->order->customer_id != $customer->id){
            throw new \Exception('The provided customer id is mismatched with order customer id');
        }
        $review = new ProductReview();
        $review->customer_id = $customer->id;
        $review->language_id = $validData['language_id'];
        $review->comment = trim($validData['comment']);
        $review->rating = $validData['rating'];
        $review->order_item_id = $validData['order_item_id'];
        $review->is_approved = false;
        $review->save();
        if (is_array($validData['gallery']) && count($validData['gallery']) > 0) {
            $this->saveApiProductReviewGallery($validData, $review);
        } else if ($validData['gallery'] != null) {
            $this->saveProductReviewGallery($validData, $review);
        }
        return $review;
    }

    public function saveProductReviewGallery($validData, $review)
    {
        $gallery_array = explode(",", $validData['gallery']);
        if (count($gallery_array) == 0) {
            throw new \Exception(__('Unable to create an empty review.'));
        }
        foreach ($gallery_array as $image):
            $productReviewGallery = new ProductReviewGallery();
            $productReviewGallery->product_review_id = $review->id;
            $productReviewGallery->image = $image;
            $productReviewGallery->save();
        endforeach;
    }

    public function saveApiProductReviewGallery($validData, $review)
    {
        $gallery_array = $validData['gallery'];
        foreach ($gallery_array as $image):
            $path = $image->store('images');
            $productReviewGallery = new ProductReviewGallery();
            $productReviewGallery->product_review_id = $review->id;
            $productReviewGallery->image = $path;
            $productReviewGallery->save();
        endforeach;
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function customerStoreReviews(Customer $customer)
    {
        return StoreReview::query()
            ->where('customer_id', $customer->id)
            ->paginate();
    }

    /**
     * @param Customer $customer
     * @param array $validData
     * @return StoreReview
     * @throws \Exception
     */
    public function createStoreReviewByCustomer(Customer $customer, array $validData)
    {
        if ($validData['rating'] > 5){
            throw new \Exception('Invalid rating data');
        }
        if (!isset($validData['store_order_id'])){
            throw new \Exception('Invalid Store data');
        }
        $storeOrder = StoreOrder::findOrFail($validData['store_order_id']);
        if ($storeOrder->order->customer_id != $customer->id){
            throw new \Exception('Invalid Store data');
        }
        $review = new StoreReview();
        $review->customer_id = $customer->id;
        $review->language_id = $validData['language_id'];
        $review->comment = trim($validData['comment']);
        $review->rating = $validData['rating'];
        $review->store_id = $validData['store_id'];
        $review->store_order_id = $validData['store_order_id'];
        $review->is_approved = false;
        $review->save();
        return $review;
    }

        /**
     * upload gallery
     *
     * @param array $request
     * @return string|array
     */
    public function upload(array $request) {
        if (!isset($request['file'])) {
            return (__('error'));
        }

        if (empty($request['file'])) {
            return (__('error'));
        }

        $gallery_response = [];
        foreach ($request['file'] as $file) {
            $imageName = Media::upload($file, true, false);
            $gallery_response['name'] = $imageName;
        }

        return $gallery_response;

    }

    /**
     * delete gallery image
     *
     * @param array $request
     * @return string
     */
    public function delete(array $request): string {
        if (isset($request['filename']) && !empty($request['filename'])) {

            return (__('error'));
        }
         $filename = $request['filename'];
            Media::delete($filename);

            return "success";

    }

}
