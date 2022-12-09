<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\FilterCategoryService;

class CategoryController extends Controller
{
    public function index(FilterCategoryService $request)
    {
        $baseCategories = $request->setIncludeOnlyParentCategory(true)->active();
        $categories = $baseCategories->get();

        $data = [
            'categories' => $categories
        ];

        return view('web.categories.index', $data);
    }
}
