<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use Illuminate\Http\Request;

class LogActivityController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = LogActivity::latest()->get();

        return view('admin.activity-logs.index', [
            'title' => __('Activity Logs'),
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LogActivity  $logActivity
     * @return \Illuminate\Http\Response
     */
    public function show(LogActivity $logActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LogActivity  $logActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(LogActivity $logActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LogActivity  $logActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LogActivity $logActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LogActivity  $logActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogActivity $logActivity)
    {
        //
    }
}
