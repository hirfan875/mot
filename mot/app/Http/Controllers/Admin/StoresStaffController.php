<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Service\StoreStaffService;
use Illuminate\Http\Request;

class StoresStaffController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function index(Store $store)
    {
        $records = StoreStaff::whereStoreId($store->id)->latest()->get();

        return view('admin.stores-staff.index', [
            'title' => __('Staff'),
            'records' => $records,
            'store' => $store
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function create(Store $store)
    {
        return view('admin.stores-staff.add', [
            'title' => __('Add Staff'),
            'section_title' => __('Staff'),
            'store' => $store
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Store $store
     * @param StoreStaff $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store, StoreStaff $staff)
    {
        return view('admin.stores-staff.edit', [
            'title' => __('Edit Staff'),
            'section_title' => __('Staff'),
            'row' => $staff,
            'store' => $store
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Store $store
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|max:50',
            'phone' => 'required|max:20',
            'email' => 'required|max:100|email|unique:store_staff',
            'password' => 'required|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ]);

        $storeStaffService = new StoreStaffService();
        $storeStaffService->create($request->toArray(), $store);

        return redirect()->route('admin.stores.staff', ['store' => $store->id])->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Store $store
     * @param StoreStaff $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store, StoreStaff $staff)
    {
        $request->validate([
            'name' => 'required|max:50',
            'phone' => 'required|max:20',
            'email' => 'required|max:100|email|unique:store_staff,email,' . $staff->id,
            'password' => 'sometimes|nullable|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ]);

        $storeStaffService = new StoreStaffService();
        $storeStaffService->update($staff, $request->toArray());

        return redirect()->route('admin.stores.staff', ['store' => $store->id])->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Store $store
     * @param StoreStaff $staff
     * @return \Illuminate\Http\Response
     */
    public function delete(Store $store, StoreStaff $staff)
    {
        $staff->delete();
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
        $staff = StoreStaff::findOrFail($request->id);
        $staff->status = $request->value;
        $staff->save();
    }
}
