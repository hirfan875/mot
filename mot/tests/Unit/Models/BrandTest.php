<?php

namespace Tests\Unit\Models;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BrandTest extends TestCase
{
    use DatabaseTransactions;

    public function testBrand()
    {
        $brand = Brand::factory()->create();
        $product = Product::factory()->create(['brand_id' => $brand->id]);
        $product_ = Product::query()->whereHas('brand', function(Builder $query) use ($brand){
            $query->where('id' ,$brand->id );
        })->first();
        $this->assertEquals($product->id, $product_->id);
    }

    public function testProductRelation()
    {
        $brand = Brand::factory()->create();
        $product = Product::factory()->create(['brand_id' => $brand->id]);
        $brand_ = Brand::query()->whereHas('products', function(Builder $query) use ($product){
            $query->where('id' ,$product->id );
        })->first();
        $this->assertEquals($brand->id, $brand_->id);
    }

}
