<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Service\PageService;

/**
 * TODO Take it out to Guest
 * Class PagesController
 * @package App\Http\Controllers\Customer
 */
class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $records = Page::where('slug', trim($slug))->first();
            
            if ($records == null){
                return view('errors.404');
            } else {
                $meta_title = isset($records->page_translates) ? $records->page_translates->meta_title : $records->meta_title;
                $meta_description = isset($records->page_translates) ? $records->page_translates->meta_desc : $records->meta_desc;
                $meta_keyword = isset($records->page_translates) ? $records->page_translates->meta_keyword : $records->meta_keyword;
            }

        return view('web.page.index', [
            'pages' => $records,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
            'meta_keyword' => $meta_keyword,
        ]);
    }


}
