<?php

namespace App\Service;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangePassword;
use App\Mail\RegisterVerification;
use App\Exceptions\CustomerAlreadyExistsException;
use Illuminate\Notifications\Notification;
use Monolog\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Mail\Register;

class CustomerService
{
    /**
     * create new customer
     *
     * @param array $request
     * @return Customer
     */
    public function create(array $request): Customer
    {
        $customer = new Customer();

        $customer->name = $request['name'];
        $customer->username = $request['username'];
        $customer->email = $request['email'];
        $customer->password = Hash::make($request['password']);

        if ( isset($request['phone']) ) {
            $customer->phone = $request['phone'];
        }

        if ( isset($request['birthday']) ) {
            $customer->birthday = $request['birthday'];
        }

        // if admin user add new customer, then customer automatically verified
        if ( Auth::check() ) {
            $customer->email_verified_at = now();
        }

        $customer->save();
        
        addCustomerGetResponse($request['name'],$request['email']);

        return $customer;
    }

    /**
     * update Customer
     *
     * @param Customer $customer
     * @param array $request
     * @return Customer
     */
    public function update(Customer $customer, array $request): Customer
    {
        $customer->name = $request['name'];
        $customer->phone = $request['phone'];
        $customer->birthday = $request['birthday'];

        $customer->save();

        return $customer;
    }

    /**
     * update customer password
     *
     * @param Customer $customer
     * @param array $request
     * @return Customer
     */
    public function updatePassword(Customer $customer, array $request): Customer
    {
        $customer->password = Hash::make($request['password']);
        $customer->save();

        return $customer;
    }

    /**
     * update customer password
     *
     * @param Customer $customer
     * @param array $request
     * @return Customer
     */
    public function sendChangePasswordMessage($customer, array $request) {

        $valueArray = [
            'subject' => __('Password has been changed'),
            'message' => $request['password'],
        ];
        return Mail::to($customer->email)->send(new ChangePassword($valueArray['message'], $valueArray['subject'], $customer));
    }


    public function via($notifiable)
    {
        return ['mail'];
    }
    /**
     * @param $customer
     * @param array $request
     */
    public function sendVerifyMessage($customer, array $request) {

//        $valueArray = [
//            'subject' => __('Customer email verification.'),
//            'message' => $request['register_password'],
//        ];
//        return Mail::to($customer->email)->send(new RegisterVerification($valueArray['message'], $valueArray['subject'], $customer));
//        
         $valueArray = [
            'subject' => __('Welcome on Mall of Turkeya.'),
            'message' => __('You are successfully registered.'),
        ];
        Mail::to($customer->email)->send(new Register($valueArray['message'], $valueArray['subject'], $customer));
    }

    /**
     * Add Identity Number Customer
     *
     * @param Customer $customer
     * @param array $request
     * @return Customer
     */
    public function addIdentityNumber(Customer $customer, array $request): Customer {
        $customer->identity_number = $request['identity_number'];
        $customer->save();
        return $customer;
    }

    /**
     * @param $email
     * @return Customer|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByEmail($email)
    {
        return Customer::where('email', $email)->first();
    }

    public function getCustomerByEmail($email)
    {
        return Customer::query()
            ->where('email', $email)
            ->where('is_guest', 0)
            ->first();
    }

    public function getGuestCustomerByEmail($email)
    {
        return Customer::query()
            ->where('email', $email)
            ->where('is_guest', 1)
            ->first();
    }

    /**
     * @param array $request
     * @return Customer
     */
    public function createGuestCustomer(array $request): Customer
    {
        /*will remove this if check after implemented force guest*/
        $customer = $this->getCustomerByEmail($request['email']);
        if($customer) {
            throw new \Exception('You already have an account. Please login to your account.');
        }

        $customer = $this->getGuestCustomerByEmail($request['email']);
        if($customer) {
            return $customer;
        }
        $customer = new Customer();
        $customer->name = $request['name'];
        if(isset($request['username'])) {
            $customer->username = $request['username'];
        }
        $customer->email = $request['email'];
        if(isset($request['phone'])) {
            $customer->phone = $request['phone'];
        }
        $this->setCustomerIsGuest($request , $customer);

        $customer->save();
        
        

        if($customer->is_guest == Customer::TYPEACCOUNT){
            Auth::guard('customer')->login($customer);
        }
        
        addCustomerGetResponse($request['name'],$request['email']);

        return $customer;
    }

    function setCustomerIsGuest($request, $customer )
    {
        if(isset($request['register_guest'])){
            if($request['register_guest'] == false){
                $customer->is_guest = Customer::TYPEGUEST ;
            } else {
               $customer->is_guest = Customer::TYPEACCOUNT ;
            }
        }
        return $customer;
    }

}
