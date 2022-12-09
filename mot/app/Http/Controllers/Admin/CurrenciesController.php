<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Service\CurrencyService;

class CurrenciesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:currencies-list|currencies-create|currencies-edit|currencies-delete', ['only' => ['index','store']]);
        $this->middleware('permission:currencies-create', ['only' => ['create','store']]);
        $this->middleware('permission:currencies-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:currencies-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Currency::all();

        return view('admin.currencies.index', [
            'title' => __('Currencies'),
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.currencies.add', [
            'title' => __('Add Currency'),
            'section_title' => __('Currencies')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        return view('admin.currencies.edit', [
            'title' => __('Edit Currency'),
            'section_title' => __('Currencies'),
            'row' => $currency
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:50',
            'base_rate' => 'required|max:20',
            'code' => 'required|max:3|unique:currencies',
            'symbol' => 'required|max:10',
        ]);

        $currencyService = new CurrencyService();
        $currencyService->create($request->toArray());

        return redirect()->route('admin.currencies')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'title' => 'required|max:50',
            'base_rate' => 'required|max:20',
            'code' => 'required|max:3|unique:currencies,code,'.$currency->id,
            'symbol' => 'required|max:10',
        ]);

        $currencyService = new CurrencyService();
        $currencyService->update($currency, $request->toArray());

        return redirect()->route('admin.currencies')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function delete(Currency $currency)
    {
        $currency->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * update status
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->status = $request->value;
        $currency->save();
    }

    /**
     * set selected currency as default
     *
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function setDefault(Currency $currency)
    {
        /** update all currencies default status except selected */
        Currency::where('id', '<>', $currency->id)->update(['is_default' => null]);

        /** update selected currency default flag */
        $currency->is_default = 'Yes';
        $currency->save();

        return back()->with('success', __('Record updated successfully.'));
    }
}
