<?php

namespace Tests\Unit\Service;


use App\Models\HomepageSorting;
use App\Models\Slider;
use App\Models\SponsorCategory;
use App\Models\SponsorSection;
use App\Service\HomePageSectionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HomePageSectionServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetSections()
    {
        $homePageService = new HomePageSectionService();
        $sections = $homePageService->getSections();

        $this->assertCount(8, $sections);
    }


    public function testGetDisabledSections()
    {
        $sponsored = SponsorSection::create(['status'=>false, 'title'=> 'Not shown']);
        $sponsoredCat = SponsorCategory::create([
            'sponsor_section_id'=>$sponsored->id,
            'title'=>'Buy Unlimited Shoes',
            'button_text'=>'Shop Now',
            'button_url'=>'https://shoes-unlimited.com/',
        ]);
        HomepageSorting::create(['sortable_type' => SponsorSection::class, 'sortable_id' => $sponsored->id, 'sort_order' => 13]);


        $homePageService = new HomePageSectionService();
        $sections = $homePageService->getSections();

        // dont see the added section
        $this->assertCount(8, $sections);
    }

    public function testGetEnabledSections()
    {
        $sponsored = SponsorSection::create(['status'=>true, 'title'=> 'Not shown']);
        $sponsoredCat = SponsorCategory::create([
            'sponsor_section_id'=>$sponsored->id,
            'title'=>'Buy Unlimited Shoes',
            'button_text'=>'Shop Now',
            'button_url'=>'https://shoes-unlimited.com/',
        ]);
        HomepageSorting::create(['sortable_type' => SponsorSection::class, 'sortable_id' => $sponsored->id, 'sort_order' => 13]);


        $homePageService = new HomePageSectionService();
        $sections = $homePageService->getSections();

        // see the added section
        $this->assertCount(9, $sections);
    }


}



