<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Service\StoreStaffService;
use Illuminate\Http\Request;

class ProfileController extends Controller
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

    // show all records
    public function showForm()
    {
        return view('seller.profile.index', [
            'title' => __('Profile'),
            'row' => auth()->user()
        ]);
    }

    // form process
    public function formProcess(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|max:50',
            'phone' => 'required|max:20',
            'email' => 'required|max:100|email|unique:store_staff,email,'.$user->id,
            'password' => ['sometimes', 'nullable', 'min:6', 'max:20', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/'],

        ]);

        $storeStaffService = new StoreStaffService();
        $storeStaffService->updateProfile($user, $request->toArray());

        return back()->with('success', __('Profile updated successfully.'));
    }
}
