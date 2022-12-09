<?php

namespace Tests\Feature\Guest\HomePage;

use App\Models\HomepageSorting;
use App\Models\Language;
use App\Models\Slider;
use App\Models\SponsorCategory;
use App\Models\SponsorSection;
use App\Models\TrendingProduct;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    /**
     * Check If home page is functioning
     *
     * @return void
     */
    public function testHomePage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     *
     */
    public function testHomePageSlider()
    {
        $slider = Slider::first();
        $response = $this->get('/');
        $response->assertSeeText(html_entity_decode($slider->button_text));
        $response->assertSee($slider->button_url);

        $response->assertStatus(200);
    }

    public function testHomePageSponsoredCategories()
    {
        $sponsored = SponsorSection::create(['status'=>true, 'title'=> 'Not shown']);
        $sponsoredCat = SponsorCategory::create([
            'sponsor_section_id'=>$sponsored->id,
            'title'=>$this->faker->word(3),
            'button_text'=>$this->faker->word(6),
            'button_url'=>$this->faker->url,
        ]);
        HomepageSorting::create(['sortable_type' => SponsorSection::class, 'sortable_id' => $sponsored->id, 'sort_order' => 13]);

        $response = $this->get('/');
        $response->assertSee($sponsoredCat->button_url);

        $response->assertStatus(200);
    }

    public function testHomePageTrendingProducts()
    {
        TrendingProduct::unguard(true);
        $trendingProduct = TrendingProduct::create(['status'=>false, 'title'=> 'Trending Product Title']);
        HomepageSorting::create(['sortable_type' => TrendingProduct::class, 'sortable_id' => $trendingProduct->id, 'sort_order' => 1]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $this->markTestIncomplete('Need attention to assert');
    }

    public function testHomePageDisabledSponsoredCategories()
    {
        $sponsored = SponsorSection::create(['status'=>false, 'title'=> 'Not shown']);
        $sponsoredCat = SponsorCategory::create([
            'sponsor_section_id'=>$sponsored->id,
            'title'=>'Buy Unlimited Shoes',
            'button_text'=>'Shop Now',
            'button_url'=>'https://shoes-unlimited.com/',
        ]);
        HomepageSorting::create(['sortable_type' => SponsorSection::class, 'sortable_id' => $sponsored->id, 'sort_order' => 13]);

        $response = $this->get('/');
        $response->assertDontSeeText($sponsoredCat->title);
        $response->assertDontSee($sponsoredCat->button_url);
        $response->assertStatus(200);
    }

    public function testGuestCanSeeLanguageDropDown()
    {
        $language =  Language::create(['status' => true ,'code' =>'ar', 'title'=> 'Arabic',  'native' => 'العربية']);

        $response = $this->get('/');
        $response->assertSeeText($language->native);
        $response->assertStatus(200);
    }
}
