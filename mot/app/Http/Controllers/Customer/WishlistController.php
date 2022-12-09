<?php
namespace App\Http\Controllers\Customer;
use App\Extensions\Response;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Wishlist;
use App\Service\OrderService;
use Illuminate\Http\Request;
class WishlistController extends Controller
{
    public function index(Request  $request)
    {
        try {
            $customer = Customer::findOrFail(Auth()->user()->id);
            $wishlists = Wishlist::with('product')->where('customer_id',Auth()->user()->id)->get();
            $wishlists = $wishlists->sortByDesc('created_at');
        } catch (\Exception $exc) {
            return Response::error('customer.wishlist', __($exc->getMessage()), [], $request);
        }
        return Response::success('customer.wishlist', [
            'customer' => $customer,
            'wishlists' => $wishlists,
        ], $request);
    }}





