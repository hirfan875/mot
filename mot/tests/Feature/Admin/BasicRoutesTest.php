<?php

namespace Tests\Feature\Admin;

use App\Models\Attribute;
use App\Models\City;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BasicRoutesTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAdminCanVisitDashboardPage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText('Dashboard');
        //$response->assertSeeText('Total no. of Direct Orders');
    }


    /**
     * @return void
     */
    public function testAdminCanVisitProfilePage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.profile'));

        $response->assertStatus(200);
        $response->assertSeeText('Dashboard');
//        $response->assertSeeText('Total no. of Direct Orders');
    }

    /**
     * @return void
     */
    public function testAdminCanVisitSettingsPage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.settings'));

        $response->assertStatus(200);
        $response->assertSeeText('Dashboard');
//        $response->assertSeeText('Total no. of Direct Orders');
    }


    /**
     * @return void
     */
    public function testAdminCanVisitMediaSettingsPage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.media.settings'));

        $response->assertStatus(200);
    }


    /**
     * @return void
     */
    public function testAdminCanVisitPagesPage()
    {
//Route::get('/pages/add', 'PagesController@create')->name('pages.add');
//Route::get('/pages/edit/{page}', 'PagesController@edit')->name('pages.edit');
//Route::get('/pages/delete/{page}', 'PagesController@delete')->name('pages.delete');

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.pages'));

        $response->assertStatus(200);
        $response->assertSeeText('Dashboard');
//        $response->assertSeeText('Total no. of Direct Orders');
    }

    /**
     * @return void
     */
    public function testAdminCanVisitCategoryPage()
    {
//Route::get('/categories/add', 'CategoriesController@create')->name('categories.add');
//Route::get('/categories/edit/{category}', 'CategoriesController@edit')->name('categories.edit');
//Route::get('/categories/delete/{category}', 'CategoriesController@delete')->name('categories.delete');
//Route::get('/categories/sorting', 'CategoriesController@sorting')->name('categories.sorting');

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.categories'));

        $response->assertStatus(200);
        $response->assertSeeText('Dashboard');
        //$response->assertSeeText('Total no. of Direct Orders');
    }



    /**
     * @return void
     */
    public function testAdminCanVisitBrandsPage()
    {
        // brands routes
//        Route::get('/brands/add', 'BrandsController@create')->name('brands.add');
//        Route::get('/brands/edit/{brand}', 'BrandsController@edit')->name('brands.edit');
//        Route::get('/brands/delete/{brand}', 'BrandsController@delete')->name('brands.delete');
//        Route::get('/brands/sorting', 'BrandsController@sorting')->name('brands.sorting');
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.brands'));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAdminCanVisitStoresPage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.stores'));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAdminCanVisitStorestaffPage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.stores.staff', 1));

        $response->assertStatus(200);
    }

    // sliders routes
    /**
     * @return void
     */
    public function testAdminCanVisitSlidersPage()
    {
//        Route::get('/sliders/add', 'SlidersController@create')->name('sliders.add');
//        Route::get('/sliders/edit/{slider}', 'SlidersController@edit')->name('sliders.edit');
//        Route::get('/sliders/delete/{slider}', 'SlidersController@delete')->name('sliders.delete');
//        Route::get('/sliders/sorting', 'SlidersController@sorting')->name('sliders.sorting');
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.sliders'));

        $response->assertStatus(200);
    }

    // countries routes
    /**
     * @return void
     */
    public function testAdminCanVisitCountriesPage()
    {
//        Route::get('/countries/add', 'CountriesController@create')->name('countries.add');
//        Route::get('/countries/edit/{country}', 'CountriesController@edit')->name('countries.edit');
//        Route::get('/countries/delete/{country}', 'CountriesController@delete')->name('countries.delete');
//        Route::get('/countries/set-default/{country}', 'CountriesController@setDefault')->name('countries.set.default');
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.countries'));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAdminCanVisitCitiesPage()
    {
//        Route::get('/countries/{country}/cities/add', 'CitiesController@create')->name('cities.add');
//        Route::get('/countries/{country}/cities/edit/{city}', 'CitiesController@edit')->name('cities.edit');
//        Route::get('/countries/{country}/cities/delete/{city}', 'CitiesController@delete')->name('cities.delete');
        $country =Country::factory()->create();
        City::factory()->create(['country_id'=>$country->id]);
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.cities', $country->id));

        $response->assertStatus(200);
    }

    // contact inquiry routes
    /**
     * @return void
     */
    public function testAdminCanVisitContactInquiriesPage()
    {
//        Route::get('/contact-inquiries/detail/{inquiry}', 'ContactInquiriesController@detail')->name('contact.inquiries.detail');
//        Route::get('/contact-inquiries/delete/{inquiry}', 'ContactInquiriesController@delete')->name('contact.inquiries.delete');
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.contact.inquiries'));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAdminCanVisitCustomersPage()
    {
        // customers routes
//        Route::get('/customers/add', 'CustomersController@create')->name('customers.add');
//        Route::get('/customers/edit/{customer}', 'CustomersController@edit')->name('customers.edit');
//        Route::get('/customers/delete/{customer}', 'CustomersController@delete')->name('customers.delete');
        $customer = Customer::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.customers', $customer->id));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAdminCanVisitAddressesPage()
    {
        // customer addresses routes
//        Route::get('/customers/{customer}/addresses/add', 'CustomersAddressesController@create')->name('addresses.add');
//        Route::get('/customers/{customer}/addresses/edit/{address:id}', 'CustomersAddressesController@edit')->name('addresses.edit');
//        Route::get('/customers/{customer}/addresses/delete/{address:id}', 'CustomersAddressesController@delete')->name('addresses.delete');
//        Route::get('/customers/{customer}/addresses/default/{address:id}', 'CustomersAddressesController@makeDefault')->name('addresses.default');
        $customer = Customer::factory()->create();
        $customerAddress = CustomerAddress::factory(10)->create(['customer_id'=>$customer->id]);

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.addresses', $customer->id));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAdminCanVisitCurrenciesPage()
    {
        // currencies routes
//        Route::get('/currencies/add', 'CurrenciesController@create')->name('currencies.add');
//        Route::get('/currencies/edit/{currency}', 'CurrenciesController@edit')->name('currencies.edit');
//        Route::get('/currencies/delete/{currency}', 'CurrenciesController@delete')->name('currencies.delete');
//        Route::get('/currencies/set-default/{currency}', 'CurrenciesController@setDefault')->name('currencies.set.default');
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.currencies'));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAdminCanVisitLanguagePage()
    {
        // languages routes
//        Route::get('/languages/add', 'LanguagesController@create')->name('languages.add');
//        Route::get('/languages/edit/{language}', 'LanguagesController@edit')->name('languages.edit');
//        Route::get('/languages/delete/{language}', 'LanguagesController@delete')->name('languages.delete');
//        Route::get('/languages/set-default/{language}', 'LanguagesController@setDefault')->name('languages.set.default');
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.languages'));

        $response->assertStatus(200);
    }

    // attributes routes
    /**
     * @return void
     */
    public function testAdminCanVisitAttributesPage()
    {
//        Route::get('/attributes/add', 'AttributesController@create')->name('attributes.add');
//        Route::get('/attributes/edit/{attribute}', 'AttributesController@edit')->name('attributes.edit');
//        Route::get('/attributes/delete/{attribute}', 'AttributesController@delete')->name('attributes.delete');
//        Route::get('/attributes/sorting', 'AttributesController@sorting')->name('attributes.sorting');
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.media.settings'));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAdminCanVisitAttributeOptionPage()
    {
        // attribute options routes
//        Route::get('/attributes/{attribute}/options/add', 'AttributesOptionsController@create')->name('attributes.options.add');
//        Route::get('/attributes/{attribute}/options/edit/{option}', 'AttributesOptionsController@edit')->name('attributes.options.edit');
//        Route::get('/attributes/{attribute}/options/sorting', 'AttributesOptionsController@sorting')->name('attributes.options.sorting');
        $attribute = Attribute::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.attributes.options', $attribute->id));

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testAdminCanVisitProductsPage()
    {
        // products routes
//        Route::get('/products/add', 'ProductsController@create')->name('products.add');
//        Route::get('/products/edit/{product}', 'ProductsController@edit')->name('products.edit');
//        Route::get('/products/delete/{product}', 'ProductsController@delete')->name('products.delete');
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('admin.products'));

        $response->assertStatus(200);
    }
}
