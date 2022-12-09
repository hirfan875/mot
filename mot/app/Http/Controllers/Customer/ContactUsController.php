<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Service\ContactusService;
use Illuminate\Http\Request;
use App\Extensions\Response;
use Illuminate\Support\Facades\Auth;
use App\Service\NotificationService;

class ContactUsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('web.contact-us.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request, NotificationService $notificationService) {
        try {

            $request->validate([
                'name' => 'required|max:200',
                'email' => 'required|string|email|max:255',
                'phone' => 'required|numeric',
                'subject' => 'required|max:200',
                'message' => 'required',
            ]);

            $ContactService = new ContactusService();
            $customer_id = null;
            if (Auth::guard('customer')->check()) {
                $customer_id = Auth::guard('customer')->user()->id;
            }
            $ContactService->create($request->all() + ['customer_id' => $customer_id],$notificationService);

        } catch (\Exception $exc) {
            return Response::error('contact-us', __('Unable to submit form.'), $exc, $request, 400);
        }
        return redirect()->route('contact-us')->with('success', __('Your Inquiry has been sent successfully !'));
    }
}
