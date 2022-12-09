<?php


namespace Tests\Unit\Service;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Store;
use App\Service\MoTCartService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartTests extends TestCase
{
    use DatabaseTransactions;
    const CART_NAME = 'mot-cart';
    public function testAddToCart()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $cartService = new MoTCartService();
        $cartService->addItem($product, rand(1, 5));
        $this->assertCount(1, $cartService->getContent());
    }

    /**
     * should throw an exception
     * @throws \Exception
     */
    public function testAddInvalidProductToCart()
    {
        $this->expectException(\Exception::class);
        /** @var Product $product */
        $product = Product::factory()->create(['type'=> Product::TYPE_VARIABLE]);
        $cartService = new MoTCartService();
        $cartService->addItem($product, rand(1, 5));
        $this->assertCount(1, $cartService->getContent());
    }



    public function testCartTotal()
    {
        $price = 100;
        $deliery_fee = 5;
        /** @var Product $product */
        $product = Product::factory()->create(['price'=>$price , 'delivery_fee'=> $deliery_fee ]);
        $quantity = 2;

        $cartService = new MoTCartService();
        $cartService->addItem($product, $quantity);

        $this->assertEquals($quantity*($price+$deliery_fee) ,$cartService->getTotal());
    }
    public function testCartUpdateQuantity()
    {
        $product = Product::factory()->create();

        $cartService = new MoTCartService();
        $cartService->addItem($product, 1);
        $cartService->updateItem($product, 2);

        /** @var CartProduct $cartProduct */
        $cartProduct = $cartService->getContent()->first();
        $this->assertEquals(2 ,$cartProduct->quantity);
    }


    public function testCartRemoveItem()
    {
        $product = Product::factory()->create();


        $cartService = new MoTCartService();
        $cartItem = $cartService->addItem($product, 1);
        $cartService->removeItem($cartItem);

        $this->assertCount(0, $cartService->getContent());
    }

}
