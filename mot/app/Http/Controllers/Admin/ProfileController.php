<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\UserService;
use App\Models\User;

class ProfileController extends Controller
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

    // show all records
    public function showForm()
    {
        return view('admin.profile.index', [
            'title' => __('Profile'),
            'row' => auth()->user()
        ]);
    }

    // form process
    public function formProcess(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => ['sometimes', 'nullable', 'min:6', 'max:20', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/'],
            'image' => 'sometimes|mimes:jpg,jpeg,png'
        ]);

        $userService = new UserService();
        $userService->update($user, $request->toArray());

        return back()->with('success', __('Profile updated successfully.'));
    }
}
