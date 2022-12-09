<?php
namespace Tests\Unit\Models;

use App\Models\StoreStaff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreStaffTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testFactoryPassword(){
        /** @var StoreStaff $seller */
        $seller = StoreStaff::factory()->create(['email'=>'vendor@mot.com']);
        $this->assertTrue(Hash::check('password',$seller->password));
    }


    public function testFactoryEmail(){
        /** @var StoreStaff $vendor */
        $email = 'vendor@mot.com';
        StoreStaff::factory()->create(['email'=> $email]);

        $vendor = StoreStaff::query()->where('email', $email)->first();

        $this->assertEquals($vendor->email , $email);
    }


    public function testAuth(){
        /** @var StoreStaff $vendor */
        $seller = StoreStaff::factory()->create(['password' => Hash::make('password')]);
        $customerGuard = Auth::guard('seller');
        $success = $customerGuard
            ->attempt(['email' => $seller->email , 'password'=>'password'], false);
        $this->assertTrue($success);
    }

}
