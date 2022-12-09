<?php

namespace App\Http\Controllers\Customer;

use App\Extensions\Response;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Service\AttributeService;
use App\Service\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class CompareProductsController extends Controller
{
    protected $compareSessionKey = 'compare-product';

    public function index(Request $request)
    {
        try {
            $key = $this->compareSessionKey;
            $compareProduct = unserialize(Session::get($key)); // [137,138]
            if (empty($compareProduct)) {
                throw new \Exception('No compare products');
            }
            $products = new ProductService();
            $products = $products->getCompareProducts($compareProduct);
            //fixed attributes with dynamic attributes in compare products page
            $fixedAttributes = [
                'product-image' => __('Product Image'),
                'title' => __('Product Title'),
                'price' => __('Price'),
                'rating' => __('Rating'),
                'data' => __('Description'),
                'add-to-cart' => __('Add To Cart'),
                'zzremoved' => __('Remove'),
            ];
            $attribute_names = [];

            foreach ($products as $productItem) {
                foreach ($productItem->attributes as $attributeItem) {
                    $fixedAttributes[$attributeItem->attribute_by_id->slug] = $attributeItem->attribute_by_id->title;
                }
            }

            $attributeService = new AttributeService();
            $productService = new ProductService();
            $variableAttributes = [];
            /** @var Product $productItem */
            foreach ($products as $productItem) {
                foreach ($productItem->variations as $variationsItem) {
                    foreach ($variationsItem->variation_attributes as $varkey => $product_attributes) {
                        $attributeRow = $attributeService->getAttributeID($product_attributes->option_id);
                        if (!$attributeRow->has('parent')) {
                            continue;
                        }
                        $product_options = $productService->getProductAttributeOptionByID($attributeRow->parent_id , $product_attributes->product_id);
                        $options = $attributeService->getAttributeTitleByID($product_options);
                        $variableAttributes[$attributeRow->parent->slug][$productItem->id] = implode(", ", $options);
                    }
                }
            }

        } catch (\Exception $exc) {
            return Response::back(['message' => __($exc->getMessage())]);
        }
        return Response::success('web.products.compare', [
            'currency' => getCurrency(),
            'products' => $products,
            'fixedAttributes' => $fixedAttributes,
            'variableAttributes' => $variableAttributes,
        ], $request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function addCompareProduct(Request $request, $id)
    {

        $key = $this->compareSessionKey;
        try {

            $compareProduct = Session::get($key);
            if ($compareProduct == null) {
                $compareIds = [$id];
            }
            if ($compareProduct != null) {
                $compareIds = unserialize($compareProduct);
                $compareIds[] = $id;
            }

            Session::put($key, serialize($compareIds));
        }
        catch (\Exception $exc){
            $logger = getLogger('compare-product');
            $logger->critical($exc->getMessage() , [$request->toArray()]);
            return Response::sendToJson(500, __('Something went wrong'), $request);
        }

        return Response::success(null, [], $request);
    }

    public function removeCompareProduct($id, Request $request)
    {
        try {
            $sessionKey = $this->compareSessionKey;
            $compareProduct = unserialize(Session::get($sessionKey));
            $key = array_search($id, $compareProduct); //get the index of product from session array
            if($key !== false) {
                unset($compareProduct[$key]);
            }
            Session::put($sessionKey, serialize($compareProduct));

        } catch (\Exception $exc){
            $logger = getLogger('compare-product');
            $logger->critical($exc->getMessage() , [$request->toArray()]);
            return Response::sendToJson(500, __('Something went wrong'), $request);
        }
        return Response::success(null, [], $request);
    }

}
