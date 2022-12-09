<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\CategoryService;
use App\Service\StoreService;
use App\Extensions\Response;
use App\Models\Store;
use Monolog\Logger;

class SellerController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showRegisterForm()
    {
        $categoryService = new CategoryService();
        $data = [
            'categories' => $categoryService->getTopLevelCategories()
        ];

        return view('web.auth.register-seller', $data);
    }

    public function saveSeller(Request $request)
    {
        $logger = getLogger('seller' , Logger::DEBUG , 'logs/seller.log');
        $logger->info('creating Store',$request->toArray());
        $request->validate([
            'name'          => 'required|max:50|unique:stores',
            'email'         => 'required|max:100|email|unique:store_staff|unique:stores',
            'password'      => 'required|confirmed|min:6|max:20',
            'accept'        => 'required',
            'phone'         => 'required|numeric',
        ]);
        $logger->info('validated Store',$request->toArray());

        $storeService = new StoreService();
        $storeService->registerFromWeb($request->toArray());
        $logger->info('created Store',$request->toArray());

        return redirect()->route('seller-registered-success');
    }

    public function registeredSuccess()
    {
        return view('web.store.registered-success');
    }

}
