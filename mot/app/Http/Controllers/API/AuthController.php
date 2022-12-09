<?php

namespace App\Http\Controllers\API;

use App\Helpers\UtilityHelpers;
use App\Models\Cart;
use App\Service\ApiCartService;
use App\Service\MoTCartService;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Models\Customer;
use App\Models\UserDevices;
use App\Service\CustomerService;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends BaseController
{

    public function signin(Request $request, NotificationService $notificationService)
    {
        $checkUser = Customer::where('email', $request->email)->first();
        if($checkUser == null) {
            return $this->sendError(__('User not registered'));
        }

        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {

            $customer = Auth::guard('customer')->user();
            if ($customer != null) {
                $customer_id = $customer->id;

                $customer->no_of_login = $customer->no_of_login + 1;
                $customer->save();
            }

            /*save device token information*/
            $deviceArray = $notificationService->setupUserDeviceArray($request->all());
            $notificationService->saveOrUpdateDeviceToken($deviceArray);
            $userDevice = UserDevices::where(['token' => $request['device_token']])->first();

            if (isset($request->session_id) && $request->session_id != null) {
                $cartService = new ApiCartService($request->session_id);
                $cartService->getAbandonedCart();
                $cartService->updateCartCustomer();
            } else {

                $getLastCart = '';
                $getLastCart = Cart::where('customer_id', $customer_id)->where('status', '!=', Cart::TERMINATED_ID)->with('cart_products')->has('cart_products')->latest()->first();

                if ($getLastCart) {
                    Session::put('cart-session-id', $getLastCart->session_id);
                }
            }

            $authUser = Auth::guard('customer')->user();
            $success['token'] = $authUser->createToken('MyAuthApp')->plainTextToken;
            $success['customer'] = $authUser;
            $success['device'] = $userDevice;
            $success['cartSessionID'] = UtilityHelpers::getCartSessionId() != null ? UtilityHelpers::getCartSessionId() : $request->session_id;

            return $this->sendResponse($success, 'User signed in');
        } else {
            return $this->sendError(__('Invalid email or password'));
        }
    }

    /**
     * @param Request $request
     * @param NotificationService $notificationService
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request, NotificationService $notificationService)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $customer = Customer::create($input);

        $success['token'] =  $customer->createToken('MyAuthApp')->plainTextToken;
        $success['customer'] =  $customer;
        $customerinfo = new CustomerService();
        $customerinfo->sendVerifyMessage($customer,$request->toArray());

        /*save device token information*/
        $deviceArray = $notificationService->setupUserDeviceArray($input);
        $notificationService->saveOrUpdateDeviceToken($deviceArray);

        return $this->sendResponse($success, 'User created successfully.');
    }

    /**
     * @param Request $request
     * @param NotificationService $notificationService
     * @return \Illuminate\Http\Response
     */
    public function socialSignup(Request $request, NotificationService $notificationService)
    {
        $customer = Customer::where('uid', $request->uid)->first();
        if ($customer == null) {
            $customer = Customer::where('email', $request->email)->first();
        }

        $userDevice = UserDevices::where(['token' => $request['device_token']])->first();

        if ($customer != null) {

            $customer->uid = $request->uid;
            /*save device token information*/
            $deviceArray = $notificationService->setupUserDeviceArray($request->all() + ['customer_id' => $customer->id]);
            $notificationService->saveOrUpdateDeviceToken($deviceArray);

            $this->setupOldCart($request, $customer);

            $success['token'] = $customer->createToken('MyAuthApp')->plainTextToken;
            $success['customer'] = $customer;
            $success['device'] = $userDevice;
            $success['cartSessionID'] = UtilityHelpers::getCartSessionId() != null ? UtilityHelpers::getCartSessionId() : $request->session_id;

            return $this->sendResponse($success, 'User signed in');
        }

        $validator = Validator::make($request->all(), [
            'uid' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'provider' => 'required',
            'device_token' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make('Mot@123!');
        $customer = Customer::create($input);
        $customer->uid = $input['uid'];
        $customer->provider = $input['provider'];
        $customer->save();

        $customerInfo = new CustomerService();
        $customerInfo->sendVerifyMessage($customer, $request->toArray());

        /*save device token information*/
        $deviceArray = $notificationService->setupUserDeviceArray($input + ['customer_id' => $customer->id]);
        $notificationService->saveOrUpdateDeviceToken($deviceArray);

        if (isset($request->session_id) && $request->session_id != null) {
            $request['session_id'] = $request->session_id;
        }

        $this->setupOldCart($request, $customer);

        $success['token'] = $customer->createToken('MyAuthApp')->plainTextToken;
        $success['customer'] = $customer;
        $success['device'] = $userDevice;
        $success['cartSessionID'] = UtilityHelpers::getCartSessionId() != null ? UtilityHelpers::getCartSessionId() : $request->session_id;

        return $this->sendResponse($success, 'User signed in');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());
        }
        $status = Password::broker('customer')->sendResetLink(
            $request->only('email')
        );
        $status == Password::RESET_LINK_SENT ? back()->with('status', __($status)) : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);

        return $this->sendResponse([], __('We have sent you the password reset link, Check your email to continue!'));
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::broker('customer')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                    'is_guest' => false,
                ])->save();
                    Auth::guard('customer')->login($user);
                event(new PasswordReset($user));
            }
        );
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        $status == Password::PASSWORD_RESET
            ? redirect()->route('home')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        return $this->sendResponse([], __('Your password has been reset successfully.'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        if (!Auth('sanctum')->check()) {
            return $this->sendError(__('User not found.'));
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $customer = Auth('sanctum')->user();
        if (!Hash::check($request->current_password, $customer->password)) {
            return $this->sendError(__('Incorrect old password'));
        }

        $input = $request->all();
        $user['password'] = Hash::make($input['new_password']);

        $updatedPassword = Customer::where('id', $customer->id)->first()->update($user);

        if ($updatedPassword) {
            return $this->sendResponse([], __('Your password has been changed successfully.'));
        }
        return $this->sendError(__('Unable to change password.'));
    }

    private function setupOldCart(Request $request, Customer $customer)
    {
        if (isset($request->session_id) && $request->session_id != null) {
            $cartService = new ApiCartService($request->session_id);
            $cartService->getAbandonedCart();
            $cartService->updateCartCustomer();
        } else {

            $getLastCart = '';
            /*$customer = Auth::guard('customer')->user();
            if ($customer != null) {
                $customer_id = $customer->id;
            }*/
            $getLastCart = Cart::where('customer_id', $customer->id)->where('status', '!=', Cart::TERMINATED_ID)->with('cart_products')->has('cart_products')->latest()->first();

            if ($getLastCart) {
                Session::put('cart-session-id', $getLastCart->session_id);
            }
        }
    }

}
