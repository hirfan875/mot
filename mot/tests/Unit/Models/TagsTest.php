<?php
namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagsTest
    extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;



    // @tahir please read the following and discuss why you insist defining a middle table ?
 // https://laravel.com/docs/8.x/eloquent-relationships#many-to-many
    public function testGetProductsTags()
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create();

        $product = Product::factory()
            ->create();
        $tag->products()->attach($product);
        $tag->save();

        $this->assertEquals($product->title, $tag->products()->first()->title);

        $product->tags()->detach($tag);
        $product->save();
        $this->assertNUll($tag->products()->first());

    }

}
