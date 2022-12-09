<?php

namespace Tests\Unit\Service;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Service\CustomerAddressService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerAddressServiceTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * Check if the service returns count when there is no address in the system.
     *
     * @return void
     */
    public function testCount()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();

        $service = new CustomerAddressService();
        $this->assertCount(0,$service->getAllAddresses($customer->id));

    }

    /**
     * Check if the service returns count when there is no address in the system.
     *
     * @return void
     */
    public function testCountWhenAddressIsAdded()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();
        $customerAddress = CustomerAddress::factory()->create(['customer_id' => $customer]);

        $service = new CustomerAddressService();
        $this->assertCount(1,$service->getAllAddresses($customer->id));
    }

    public function testSetDefault()
    {
        /** @var CustomerAddress $customerAddress */
        $customerAddress = CustomerAddress::factory()->create();
        $this->assertFalse($customerAddress->is_default);

        $service = new CustomerAddressService();
        $service->makeDefault($customerAddress);

        $this->assertTrue($customerAddress->refresh()->is_default);
    }

    public function testSetDefaultOnlyOnThisAddress()
    {
        /** @var CustomerAddress $customerAddress */
        $irrelevantCustomerAddress = CustomerAddress::factory()->create();
        // ensure it is false to begin with
        $this->assertFalse($irrelevantCustomerAddress->refresh()->is_default);


        /** @var CustomerAddress $customerAddress */
        $customerAddress = CustomerAddress::factory()->create();
        $service = new CustomerAddressService();
        $service->makeDefault($customerAddress);


        // should stay false
        $this->assertFalse($irrelevantCustomerAddress->refresh()->is_default);
    }

    public function testEditAddress()
    {
        $request = [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress,
            'address2' => $this->faker->streetAddress,
            'address3' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'zipcode' => $this->faker->postcode,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'full' => $this->faker->phoneNumber,
        ];

        /** @var CustomerAddress $customerAddress */
        $customerAddress = CustomerAddress::factory()->create();
        $service = new CustomerAddressService();
        $service->update($customerAddress, $request);
        $customerAddress->refresh();

        // should stay false
        $this->assertEquals($request['name'] , $customerAddress->name);
    }

    public function testCreateAddress()
    {
        $request = [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress,
            'address2' => $this->faker->streetAddress,
            'address3' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'zipcode' => $this->faker->postcode,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'full' => $this->faker->phoneNumber,
        ];

        $service = new CustomerAddressService();
        /** @var CustomerAddress $customerAddress */
        $customerAddress = $service->create($request , Customer::factory()->create()->id);
        $customerAddress->refresh();

        // see if we updated
        $this->assertEquals($request['phone'] , $customerAddress->phone); // test one field only
        $this->assertEquals($request['zipcode'] , $customerAddress->zipcode); // test a few field only
        $this->assertEquals($request['country'] , $customerAddress->country); // test one field only
    }

}
