<?php

namespace App\Service;

use App\Models\Customer;
use App\Models\Wishlist;
use Illuminate\Support\Collection;

class WishlistService
{
    /**
     * get customer all wishlist products
     *
     * @param int $customer_id
     * @return Collection
     */
    public function getCustomerWishlist(int $customer_id): Collection
    {
        if (empty($customer_id)) {
            return collect([]);
        }

        return Wishlist::whereCustomerId($customer_id)->with('product')->get();
    }

    /**
     * add product to customer wishlist
     *
     * @param int $customer_id
     * @param int $product_id
     * @return string
     */
    public function add(Customer $customer, int $product_id): string
    {
        $checkWishlist = Wishlist::whereCustomerId($customer->id)->whereProductId($product_id)->first();


        if (! $checkWishlist) {
            $checkWishlist = new Wishlist();

            $checkWishlist->product_id = $product_id;
            $checkWishlist->customer_id = $customer->id;

            $checkWishlist->save();
            return __('Product has been added to wishlist');
        }

        return __('Product has been added to wishlist') ;

    }

    /**
     * remove product from customer wishlist
     *
     * @param Customer $customer
     * @param int $productid
     * @return bool
     * @throws \Exception
     */
    public function remove(Customer $customer, int $productId): bool
    {
        $wishList = Wishlist::whereCustomerId($customer->id)->whereProductId($productId)->first();
        if ($wishList) {
            return $wishList->delete();
        }
        return false;
    }

    // /**
    //  * remove product from customer wishlist
    //  *
    //  * @param Customer $customer
    //  * @param int $productid
    //  * @return bool
    //  * @throws \Exception
    //  */
    // public function removeUsingProductID(Customer $customer, int $product_id): bool
    // {
    //     $wishList = Wishlist::whereCustomerId($customer->id)->whereProductId($product_id)->first();
    //     dd($wishList);
    //     if ($wishList) {
    //         return $wishList->delete();
    //     }
    //     return false;
    // }

    public function count(Customer $customer)
    {
        return $this->getCustomerWishlist($customer->id)->count();
    }
}
