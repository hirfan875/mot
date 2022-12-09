<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Service\CustomerService;

class CustomersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:customers-list|customers-create|customers-edit|customers-delete', ['only' => ['index','store']]);
        $this->middleware('permission:customers-create', ['only' => ['create','store']]);
        $this->middleware('permission:customers-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:customers-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Customer::latest()->get();

        return view('admin.customers.index', [
            'title' => __('Customers'),
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
        return view('admin.customers.add', [
            'title' => __('Add Customer'),
            'section_title' => __('Customers')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', [
            'title' => __('Edit Customer'),
            'section_title' => __('Customers'),
            'row' => $customer
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
            'name' => 'required|max:50',
            'username' => 'required|max:25|unique:customers',
            'email' => 'required|max:100|email|unique:customers',
            'password' => 'required|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ]);

        $customerService = new CustomerService();
        $customerService->create($request->toArray());

        return redirect()->route('admin.customers')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|max:50',
            'username' => 'required|max:25|unique:customers,username,'.$customer->id,
            'email' => 'required|max:100|email|unique:customers,email,'.$customer->id,
            'password' => 'sometimes|nullable|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ]);

        $customerService = new CustomerService();
        $customerService->update($customer, $request->toArray());

        return redirect()->route('admin.customers')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function delete(Customer $customer)
    {
        try {
            CustomerAddress::whereCustomerId($customer->id)->delete();
            $customer->delete();
            return back()->with('success', __('Record deleted successfully.'));
        } catch (\Throwable $e) {
            return back()->with('error', __('You cannot delete this record because record linked with other records.'));
        }
    }

    /**
     * update status
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request)
    {
        $customer = Customer::findOrFail($request->id);
        $customer->status = $request->value;
        $customer->save();
    }
    
    /**
     * approve seller product
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function statusAll(Request $request)
    {
        if(isset($request->ids)){
            foreach ($request->ids as $val){
                $customer = Customer::find($val);
                $customer->status = true;
                $customer->save();
            }
        }
        return back()->with('success', __('Product Status update successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request)
    {
         if(isset($request->ids)){
            foreach ($request->ids as $val){
                $customer = Customer::find($val);
                CustomerAddress::whereCustomerId($val)->delete();
                $customer->delete();
            }
        }
        return back()->with('success', __('Record deleted successfully.'));
    }
}
