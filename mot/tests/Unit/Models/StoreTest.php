<?php

namespace Tests\Unit\Models;

use App\Models\Store;
use App\Models\StoreStaff;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testFactory()
    {
        $initialCount =  Store::count();
        Store::factory(10)->create();
        $this->assertEquals(10+$initialCount, Store::count());
    }


    public function testStaffCount()
    {
        /** @var Store $store */
        $store = Store::factory()->create();

        /** @var StoreStaff $staff */
        $staff = StoreStaff::factory(2)
            ->create(['store_id' => $store->id]);
        $this->assertCount(2, $store->staff);
    }


    public function testOwner()
    {
        /** @var Store $store */
        $store = Store::factory()->create();

        /** @var StoreStaff $staff */
        $vendorStaff = StoreStaff::factory()->create(['store_id' => $store->id , 'is_owner' => false]);
        $staff = StoreStaff::factory()->create(['store_id' => $store->id , 'is_owner' => true]);
        $this->assertNotNull( $store->owner);
        $this->assertEquals($staff->id, $store->owner->id);
    }

}
