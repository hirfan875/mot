<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Service\BrandService;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(BrandService $request)
    {
        $brands = $request->getAll(15);
        $data = [
            'brands' => $brands
        ];

        return view('web.brands.index', $data);
    }
}
