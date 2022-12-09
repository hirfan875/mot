<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\ProductGallery;
use App\Models\ProductReview;
use App\Models\StoreReview;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // make sure we dont have any products inserted before this one. Our tests rely on getting this one created and indexed by FT
        /** @var Product $product */
        $product = Product::factory([
            'title' => 'square house',        // unique enough so that its not found in other products
            'slug' => 'square-house',
            'meta_title'=>'this product has meta title',
            'meta_desc'=>'this product has meta description',
            'meta_keyword'=>'camera, digital-camera,canon, DSLR'
        ])->create();
        $orderItem = OrderItem::factory()->create(['product_id' =>$product->id]);
        ProductReview::factory()->count(4)->create(['order_item_id' => $orderItem->id]);
        $product->categories()->attach(Category::first()->id);

        // attach an image
        $productImage = ProductGallery::create([
            'image' => 'placeholder.jpg',
            'product_id' => $product->id
        ]);

        // TODO move this image from assets to Ordinal

        // attach tags on Top / Trending / featured
        $product->tags()->attach([Tag::TOP_ID, Tag::TRENDING_ID, Tag::FEATURED_ID]);


        $categories = Category::query()->whereNull('parent_id')->get();
        /** @var Category $category */
        foreach($categories as $category){
            $products = Product::factory()->count(10)->create()->each(function(Product $product) use($category){
                $product->categories()->attach($category);
            });
            $tags = [Tag::TOP_ID, Tag::TRENDING_ID,Tag::FEATURED_ID];
            foreach ($products as $product){
                if ($tags){
                    $product->tags()->attach(array_pop($tags));
                }
            }
        }
    }
}
