<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Service\UserService;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records = User::latest()->get();

        return view('admin.users.index', [
            'title' => __('Users'),
            'data' => $records
        ]);
//                ->with('i', ($request->input('page', 1) - 1) * 4);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        
        $title = __('User');
        return view('admin.users.show',compact('user','title'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('admin.users.add', [
            'title' => __('Add User'),
            'roles' => $roles,
            'section_title' => __('Users')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
            $roles = Role::pluck('name','name')->all();
            $userRole = $user->roles->pluck('name','name')->all();
        
        return view('admin.users.edit', [
            'title' => __('Edit User'),
            'section_title' => __('Users'),
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole,
            
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
            'email' => 'required|max:100|email|unique:users',
            'password' => 'required|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            'roles' => 'required'
        ]);

        $userService = new UserService();
        $userService->create($request->toArray());
        

        return redirect()->route('admin.users.index')->with('success', __('Record added successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|nullable|min:6|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            'roles' => 'required'
        ]);

        $userService = new UserService();
        $userService->update($user, $request->toArray());
        
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        $user->assignRole($request->input('roles'));

        return redirect()->route('admin.users.index')->with('success', __('Record updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
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
        $user = User::findOrFail($request->id);
        $user->status = $request->value;
        $user->save();
    }
    
    /**
     * approve seller product
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function statusAll(Request $request)
    {
        if(isset($request->ids)){
            foreach ($request->ids as $val){
                $user = User::find($val);
                $user->status = true;
                $user->save();
            }
        }
        return back()->with('success', __('Product Status update successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request)
    {
         if(isset($request->ids)){
            foreach ($request->ids as $val){
                $user = User::find($val);
                $user->delete();
            }
        }
        return back()->with('success', __('Record deleted successfully.'));
    }
}
