<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreStaff;
use App\Service\StoreStaffService;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $records = StoreStaff::whereStoreId($user->store_id)->whereIsOwner(0)->latest()->get();

        return view('seller.staff.index', [
            'title' => __('Staff'),
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
        return view('seller.staff.add', [
            'title' => __('Add Staff'),
            'section_title' => __('Staff')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param StoreStaff $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreStaff $staff)
    {
        return view('seller.staff.edit', [
            'title' => __('Edit Staff'),
            'section_title' => __('Staff'),
            'row' => $staff
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
            'phone' => 'required|max:20',
            'email' => 'required|max:100|email|unique:store_staff',
            'password' => 'required|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ]);

        $user = auth()->user();
        $storeStaffService = new StoreStaffService();
        $storeStaffService->create($request->toArray(), $user->store);

        return redirect()->route('seller.staff')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param StoreStaff $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreStaff $staff)
    {
        $request->validate([
            'name' => 'required|max:50',
            'phone' => 'required|max:20',
            'email' => 'required|max:100|email|unique:store_staff,email,' . $staff->id,
            'password' => 'sometimes|nullable|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ]);

        $storeStaffService = new StoreStaffService();
        $storeStaffService->update($staff, $request->toArray());

        return redirect()->route('seller.staff')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StoreStaff $staff
     * @return \Illuminate\Http\Response
     */
    public function delete(StoreStaff $staff)
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
