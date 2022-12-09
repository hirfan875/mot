<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\SponsorCategory;
use App\Models\SponsorSection;
use App\Models\TabbedSection;
use App\Models\Tag;
use App\Models\TrendingProduct;
use App\Service\HomepageSectionsService;
use App\Service\TabbedProductService;
use Illuminate\Database\Seeder;

class HomePageSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sponsoredCategories = [
            [
                'image' => 'img01.png',
                'button_text' => 'Shop Now',
                'title' => 'Sunglasses',
                'button_url' => 'https://sunglasses.com/',
            ],
            [
                'image' => 'img02.png',
                'button_text' => 'Shop Now',
                'title' => 'Kids Wear',
                'button_url' => 'https://sunglasses.com/',
            ],
            [
                'image' => 'img03.png',
                'button_text' => 'Shop Now',
                'title' => 'Sports Wear',
                'button_url' => 'https://sunglasses.com/',
            ],
        ];
        $this->seedSponsoredCategory($sponsoredCategories, 'Sponsored Categories', 1);


        $sponsoredCategories = [
            [
                'image' => 'col_img1.png',
                'button_text' => 'Shop Now',
                'title' => 'Sunglasses',
                'button_url' => 'https://sunglasses.com/',
            ],
            [
                'image' => 'col_img2.png',
                'button_text' => 'Shop Now',
                'title' => 'Women Shoes',
                'button_url' => 'https://sunglasses.com/',
            ],
            [
                'image' => 'col_img2.png',
                'button_text' => 'Shop Now',
                'title' => 'Women Shoes',
                'button_url' => 'https://sunglasses.com/',
            ],
        ];
        $this->seedSponsoredCategory($sponsoredCategories, 'Sponsored Categories 2', 5);

        $this->seedTabbedProducts();
        $this->seedTrendingProducts();


        $this->seedBanners();

    }

    public function seedTabbedProducts(): void
    {
        $tabbedProducts = [
            [
                'title' => 'Kids Products',
                'type' => 'product',
                'status' => true,
                'category_id' => null,
                'products' => Product::take(20)->get()->pluck('id')->toArray(),
                'sort_order' => 4,
            ],
            [
                'title' => 'Cosmetics Products',
                'type' => 'category',
                'status' => true,
                'category_id' => Category::first()->id,
                'sort_order' => 6,
            ],
            [
                'title' => 'Disabled Tabbed Products',
                'type' => 'products',
                'status' => false,
                'category_id' => Category::first()->id,
                'sort_order' => 9,

            ],
        ];

        foreach ($tabbedProducts as $tabbedProduct) {
            $sortOrder = $tabbedProduct['sort_order'];
            unset($tabbedProduct['sort_order']);
            $products = null;
            if (isset($tabbedProduct['products'])) {
                $products = $tabbedProduct['products'];
                unset($tabbedProduct['products']);
            }
            $tabbedProduct = TabbedSection::create($tabbedProduct);
            $homepageSectionsService = new HomepageSectionsService();
            $homepageSectionsService->set_sort_order($tabbedProduct);

            if ($products) {
                $tabbedProduct->products()->sync($products);
            }
        }
    }

    public function seedTrendingProducts()
    {
        $trendingProduct = TrendingProduct::create([
                'status' => 1,
                'type' => 'allProducts',
                'title' => 'Top Trending',
                'products_type' => 'tag',
                'tag_id' => Tag::TOP_ID,
        ]);
        $homepageSectionsService = new HomepageSectionsService();
        $homepageSectionsService->set_sort_order($trendingProduct);
    }

    public function seedBanners(): void
    {
        $imagesPath = '../public/static/assets/img/home_products/';
        $banners = [
            [
                'image' => 'addbanner.jpg',
                'button_text' => 'Shop Now',
                'title' => 'Sunglasses',
                'button_url' => 'https://sunglasses.com/',
                'sort_order' => 3,
            ],
            [
                'image' => 'addbanner2.jpg',
                'button_text' => 'Shop Now',
                'title' => 'Women Shoes',
                'button_url' => 'https://sunglasses.com/',
                'sort_order' => 7,
            ],
            [
                'image' => 'addbanner3.jpg',
                'button_text' => 'Shop Now',
                'title' => 'Women Shoes',
                'button_url' => 'https://sunglasses.com/',
                'sort_order' => 10,
            ],
        ];


        foreach ($banners as $banner) {
            $sortOrder = $banner['sort_order'];
            unset($banner['sort_order']);
            $sliderSource = app_path($imagesPath) . $banner['image'];
            $destination = storage_path(config('media.path.original')) . $banner['image'];
            if (file_exists($sliderSource)) {
                copy($sliderSource, $destination);
            }
            $banner = Banner::create($banner);
            $banner->sort()->create(['sort_order' => $sortOrder]);
        }
    }

    /**
     * @param array $sponsoredCategories
     * @param $sectionTitle
     * @param $sortOrder
     */
    public function seedSponsoredCategory(array $sponsoredCategories, $sectionTitle, $sortOrder)
    {
        $sponsored = SponsorSection::create(['title' => $sectionTitle]);
        $sponsored->sort()->create(['sort_order' => $sortOrder]);

        $imagesPath = '../public/assets/frontend/assets/img/home_products/';
        foreach ($sponsoredCategories as $sponsoredCategory) {
            $sliderSource = app_path($imagesPath) . $sponsoredCategory['image'];
            $destination = storage_path(config('media.path.original')) . $sponsoredCategory['image'];
            if (file_exists($sliderSource)) {
                copy($sliderSource, $destination);
            }
            $sponsoredCategory['sponsor_section_id'] = $sponsored->id;
            SponsorCategory::create($sponsoredCategory);
        }
    }
}
