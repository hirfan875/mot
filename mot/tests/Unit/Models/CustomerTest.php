<?php
namespace Tests\Unit\Models;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerTest  extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testFactoryPassword(){
        /** @var Customer $customer */
        $customer = Customer::factory()->create(['email'=>'customer_1@mot.com']);
        $this->assertEquals($customer->password , '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
    }


    public function testFactoryEmail(){
        /** @var Customer $customer */
        $email = 'customer_1@mot.com';
        Customer::factory()->create(['email'=> $email]);

        $customer = Customer::query()->where('email', $email)->first();

        $this->assertEquals($customer->email , $email);
    }


    public function testAuth(){
        /** @var Customer $customer */
        $customer = Customer::factory()->create(['password' => Hash::make('password')]);
        $customerGuard = Auth::guard('customer');
        $success = $customerGuard
            ->attempt(['email' => $customer->email , 'password'=>'password'], false);
        $this->assertTrue($success);
    }

}
