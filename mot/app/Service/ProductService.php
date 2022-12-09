<?php

namespace App\Service;

use App\Events\ProductPriceUpdate;
use App\Events\ProductKeywordUpdate;
use App\Imports\ProductsImport;
use App\Jobs\ResizeImageProcess;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductTranslate;
use App\Models\ProductAttribute;
use App\Models\ProductGallery;
use App\Models\StoreStaff;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Service\BrandService;
use App\Service\AttributeService;
use App\Service\ProductGalleryService;
use Storage;
use Google\Cloud\Translate\TranslateClient;


class ProductService
{
    /** @var \Monolog\Logger */
    private $logger;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->logger = getLogger('Product Service');
    }

    /**
     * create new product
     *
     * @param array $request
     * @param StoreStaff|null $seller
     * @return Product
     */
    public function create(array $request, StoreStaff $seller = null): Product
    {
        $product = new Product();
        $createdBy = Product::ADMIN;
        if (!empty($seller)) {
            $product->is_approved = false;
            $createdBy = Product::SELLER;
        }

        if (isset($request['additional-brand'])) {
            if ($request['additional-brand']) {
                /*create seller brand*/
                $brandService = new BrandService();
                $brand = $brandService->createSellerBrand($request);
                $request['brand'] = $brand->id;
                $product->is_approved = false;
            }
        }

        $this->fromRequestToProduct($request, $product);
        $product->image = Media::handle($request, 'image');
        $product->generateSlug();
        $product->created_by = $createdBy;
        $product->created_by_id = auth()->user()->id;
        $product->save();


        $results = $this->fromRequestToProductTranslate($request, $product);

        Media::saveCropImage($request, 'product_listing', $product->image);

        // save categories
        if (isset($request['categories']) && !empty($request['categories'])) {
            $product->categories()->sync($request['categories']);
        }

        // save tags
        $this->saveProductTags($product, $request, $seller);

        // save gallery
        if (isset($request['gallery']) && !empty($request['gallery'])) {
            $gallery = $this->setGallerySortOrder($request['gallery']);
            $product->saveGallery()->sync($gallery);
        }

        // save variations
        $this->saveVariations($product, $request);

        // save bundle products
        if ($request['type'] === Product::TYPE_BUNDLE) {
            $product->bundle_products()->sync($request['bundle_products']);
        }

        $product->promo_price = $product->discounted_price;
        $product->promo_source_id = $product->id;
        $product->promo_source_type = get_class($product);

        // dispatch events
        ProductPriceUpdate::dispatch($product);
        ProductKeywordUpdate::dispatch($product);
        
        return $product;
    }
    
    /**
     * create new product
     *
     * @param array $request
     * @param StoreStaff|null $seller
     * @return Product
     */
    public function trendyolCreate(array $request, StoreStaff $seller = null): Product
    {
        if (isset($request['additional-brand'])) {
            if ($request['additional-brand']) {
                /*create seller brand*/
                $brandService = new BrandService();
                $brand = $brandService->createSellerBrandTrendyol($request);
                $request['brand'] = $brand->id;
            }
        }
        
        $product = new Product();
        $createdBy = Product::TRANDYOL_AUTO;
       
        $additional_info = "";
        if(!empty($request['additional_information'])){
            foreach($request['additional_information'] as $info){
                $attribute_value = isset($info->attributeValue) ? $info->attributeValue : '';
                $additional_info .= "<p>".$info->attributeName.": ".$attribute_value." </p>";
            }
        }
        
//        $translate = new TranslateClient(['key' => 'AIzaSyDfrr28mzao7KAh_t0s4caVn-_T6OcT7Rk']);
//        $additionalInfo = $translate->translate($additional_info, ['target' => 'en']);
//        $request['additional_information'] = $additionalInfo['text'];
        $request['additional_information'] = $additional_info;
        
        $this->fromRequestToProductTrendyol($request, $product);
        $request['gallery']='';
        
        foreach($request['images'] as $row){
            $url = $row->url;
            $filename = basename($url);
            
            if($this->get_http_response_code($url) != "200"){
//                echo "error";
            }else{
                ini_set('max_execution_time', 0);
                $contents = file_get_contents($url);
                file_put_contents(public_path('storage/original/'.$request['barcode'].'-'.$filename), $contents);
                $request['gallery'] .= $request['barcode'].'-'.$filename.',';
                $gallery = rtrim($request['gallery'], ',');
                ResizeImageProcess::dispatch($request['barcode'].'-'.$filename, 'product');
            }
        }
        
        $product->is_approved = true;
        $product->generateSlug();
        $product->created_by = $createdBy;
//        $product->created_by_id = auth()->user()->id;
        $product->save();
        
        $results = $this->fromRequestToProductTranslateTrendyol($request, $product);


        // save categories
        if (isset($request['categories']) && !empty($request['categories'])) {
            $product->categories()->sync($request['categories']);
        }

        // save gallery
        if (isset($gallery) && !empty($gallery)) {
            $gallery = $this->setGallerySortOrder($gallery);
            $product->saveGallery()->sync($gallery);
        }

        // save variations
//        $this->saveVariations($product, $request);

        // dispatch events
        ProductPriceUpdate::dispatch($product);
        ProductKeywordUpdate::dispatch($product);
        
        return $product;
    }
    
    public function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
    
    /**
     * update product
     *
     * @param Product $product
     * @param array $request
     * @param StoreStaff|null $seller
     * @return Product
     */
    public function trendyolUpdate(Product $product, array $request, StoreStaff $seller = null): Product
    {
        $additional_info = "";
        if(!empty($request['additional_information'])){
            foreach($request['additional_information'] as $info){
                $attribute_value = isset($info->attributeValue) ? $info->attributeValue : '';
                $additional_info .= "<p>".$info->attributeName.": ".$attribute_value." </p>";
            }
           
        }
        
        if (isset($request['additional-brand'])) {
            if ($request['additional-brand']) {
                /*create seller brand*/
                $brandService = new BrandService();
                $brand = $brandService->createSellerBrandTrendyol($request);
                $request['brand'] = $brand->id;
            }
        }
//        $translate = new TranslateClient(['key' => 'AIzaSyDfrr28mzao7KAh_t0s4caVn-_T6OcT7Rk']);
//        $additionalInfo = $translate->translate($additional_info, ['target' => 'en']);
        $request['additional_information'] = $additional_info;
        
        $this->updateRequestToProductTrendyol($request, $product);

        $product->save();

        $results = $this->updateRequestToProductTranslateTrendyol($request, $product);
        
        // save categories
        if (isset($request['categories']) && !empty($request['categories'])) {
            $product->categories()->sync($request['categories']);
        }

        // save variations
//        $this->saveVariations($product, $request);

        // dispatch events
        ProductPriceUpdate::dispatch($product);
        ProductKeywordUpdate::dispatch($product);
        return $product;
    }
    
    /**
     * update product
     *
     * @param Product $product
     * @param array $request
     * @param StoreStaff|null $seller
     * @return Product
     */
    public function trendyolUpdateStatus(Product $product, array $request, StoreStaff $seller = null): Product
    {
        
        $this->updateTrendyolProductStatus($request, $product);

        $product->save();

        // dispatch events
        ProductPriceUpdate::dispatch($product);
        return $product;
    }

    /**
     * update product
     *
     * @param Product $product
     * @param array $request
     * @param StoreStaff|null $seller
     * @return Product
     */
    public function update(Product $product, array $request, StoreStaff $seller = null): Product
    {
        if (!empty($seller)) {
            $product->is_approved = false;
        }

        if (isset($request['additional-brand'])) {
            if ($request['additional-brand']) {
                /*create seller brand*/
                $brandService = new BrandService();
                $brand = $brandService->createSellerBrand($request);
                $request['brand'] = $brand->id;
                $product->is_approved = false;
            }
        }

        $this->updateRequestToProduct($request, $product);
        $product->image = Media::handle($request, 'image', $product);
        if (isset($request['slug'])) {
            $product->slug = str_replace(" ", "-", $request['slug']);
        }
        $product->save();

        $results = $this->updateRequestToProductTranslate($request, $product);

        Media::saveCropImage($request, 'product_listing', $product->image);

        // save categories
        if (isset($request['categories']) && !empty($request['categories'])) {
            $product->categories()->sync($request['categories']);
        }

        // save tags
        $this->saveProductTags($product, $request, $seller);
        if (isset($request['bundle_products'])) {
            $product->bundle_products()->sync($request['bundle_products']);
        }


        // save variations
        $this->saveVariations($product, $request);

        // dispatch events
        ProductPriceUpdate::dispatch($product);
        ProductKeywordUpdate::dispatch($product);
        return $product;
    }

    /**
     * save variations
     *
     * @param Product $product
     * @param array $request
     * @return void
     */
    private function saveVariations(Product $product, array $request)
    {
        if ($product->type != Product::TYPE_VARIABLE) {
            $this->deleteVariations($product->id);
            return;
        }
        $parent_product_price = 0;
        $parent_product_stock = 0;
        $active_variations = [];
        foreach ($request['variations'] as $data) {
            $variation = $this->createOrUpdateVariation($product, $data);
            $this->saveVariationAttributes($product->id, $variation->id, $data);
            $active_variations[] = $variation->id;
            $parent_product_price = $data['price'];
            $parent_product_stock = $parent_product_stock + $data['stock'];

            // dispatch events
            ProductPriceUpdate::dispatch($variation);
        }

        // update parent product stock
        $product->price = $parent_product_price;
        $product->stock = $parent_product_stock;
        $product->save();

        $this->deleteRemovedVariations($product->id, $active_variations);
    }

    /**
     * create or update variation
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    private function createOrUpdateVariation(Product $product, array $data): Product
    {

        $variation = $this->getVariation($data);

        $discountType = $data['discount_type'];

        if($data['discount_type'] == null && $data['discount'] != null) {
            $discountType = 'fixed';
        }

        if($data['discount_type'] != null && $data['discount'] == null) {
            $discountType = null;
        }

        $free_delivery = 1;
        $variation->type = Product::TYPE_VARIATION;
        $variation->parent_id = $product->id;
        $variation->slug = $product->slug;
        $variation->brand_id = $product->brand_id;
        $variation->store_id = $product->store_id;
        $variation->sku = $this->generateStoreSku($product->store_id, $data['sku']);
        $variation->store_sku = $data['sku'];
        $variation->price = $data['price'];
        $variation->discount = $data['discount'];
        $variation->discount_type = $discountType;
        $variation->promo_price = $data['price'];
        $variation->stock = $data['stock'];
        $variation->width = $data['width'];
        $variation->height = $data['height'];
        $variation->length = $data['length'];
        $variation->weight = $data['weight'];
        $variation->volume = $data['volume'];
        $variation->image = Media::handle($data, 'image', $variation);
        $variation->free_delivery = $free_delivery;

        $variation->save();

        return $variation;
    }

    /**
     * @param array $data
     * @return Product
     */
    public function getCompareProducts(array $data): Collection
    {
        $compareProduct = (new Product())->newCollection();
        if (isset($data) && !empty($data)) {
            $compareProduct = Product::with('attributes.attribute_by_id', 'variations.variation_attributes.option', 'gallery')
                ->whereIn('id', $data)
                ->get();
        }
        return $compareProduct;
    }

    /**
     * get variation
     *
     * @param array $data
     * @return Product
     */
    private function getVariation(array $data): Product
    {
        $variation = new Product();
        if (isset($data['id']) && !empty($data['id'])) {
            $variation = Product::find($data['id']);
        }

        return $variation;
    }

    /**
     * save variation attributes
     *
     * @param integer $product_id
     * @param integer $variation_id
     * @param array $data
     * @return void
     */
    private function saveVariationAttributes(int $product_id, int $variation_id, array $data)
    {
        foreach ($data['options'] as $k => $r) {

            $variation_attribute = $this->getVariationAttribute($product_id, $data['attributes'][$k], $variation_id, $r, $data);

            $variation_attribute->product_id = $product_id;
            $variation_attribute->variation_id = $variation_id;
            $variation_attribute->attribute_id = $data['attributes'][$k];
            $variation_attribute->option_id = $r;

            $variation_attribute->save();
        }
    }

    /**
     * get variation attribute
     *
     * @param integer $variation_id
     * @param integer $option_id
     * @return ProductAttribute
     */
    private function getVariationAttribute(int $product_id, int $attribute_id, int $variation_id, int $option_id, $data): ProductAttribute
    {
        if(isset($data['id'])){
            $variation_attribute = ProductAttribute::whereProductId($product_id)->whereAttributeId($attribute_id)->whereOptionId($option_id)->first();
        } else {
            $variation_attribute = ProductAttribute::whereVariationId($variation_id)->whereOptionId($option_id)->first();
        }

        if (!$variation_attribute) {
            $variation_attribute = new ProductAttribute();
        }

        return $variation_attribute;
    }

    /**
     * delete product variations
     *
     * @param integer $product_id
     * @return void
     */
    private function deleteVariations(int $product_id)
    {
        ProductAttribute::whereProductId($product_id)->delete();
        Product::whereParentId($product_id)->delete();
    }

    /**
     * delete removed variations
     *
     * @param int $product_id
     * @param array $active_variations
     * @return void
     */
    private function deleteRemovedVariations(int $product_id, array $active_variations)
    {
        $attributes = ProductAttribute::whereProductId($product_id)->whereNotIn('variation_id', $active_variations)->get();
        foreach ($attributes as $attribute) {
            $orderItems = OrderItem::where('product_id', $attribute->variation_id)->get();
            if ($orderItems->count() == 0) {
                $attribute->forceDelete();
                continue;
            }
            $attribute->delete();
        }

        Product::whereParentId($product_id)->whereNotIn('id', $active_variations)->delete();
        /*foreach ($products as $product) {
            $orderItems = OrderItem::where('product_id', $product->id)->get();
            if ($orderItems->count() == 0) {
                $product->forceDelete();
                continue;
            }
            $product->delete();
        }*/
    }

    /**
     * set gallery sort order
     *
     * @param string $gallery
     * @return array
     */
    private function setGallerySortOrder(string $gallery): array
    {
        $gallery_array = explode(",", $gallery);
        $sorting_gallery = [];

        foreach ($gallery_array as $k => $r) {
            $sorting_gallery[$r] = ['sort_order' => $k];
        }

        return $sorting_gallery;
    }

    /**
     * set product data from request
     *
     * @param array $request
     * @param Product $product
     * @return void
     */
    private function fromRequestToProduct(array $request, Product $product): void
    {
        $product->title = $request['title'][getDefaultLocaleId()];
        $product->type = $request['type'];
        $product->brand_id = $request['brand'];
        $product->store_id = $request['store'];
        $product->sku =  $request['sku'];
        $product->store_sku = $request['store_sku'];
        $product->price = isset($request['price']) ? $request['price'] : null;
        $product->discount = isset($request['discount']) ? $request['discount'] : null;
        $product->discount_type = isset($request['discount_type']) ? $request['discount_type'] : null;
        $product->stock = isset($request['stock']) ? $request['stock'] : null;
        $product->free_delivery = 1;
        $product->data = $request['data'][getDefaultLocaleId()];
        $product->meta_title = isset($request['meta_title'][getDefaultLocaleId()])  ? $request['meta_title'][getDefaultLocaleId()] : $request['title'][getDefaultLocaleId()] ;
        $product->meta_desc = isset($request['meta_desc'][getDefaultLocaleId()])  ? $request['meta_desc'][getDefaultLocaleId()] : $request['data'][getDefaultLocaleId()] ;
        $product->meta_keyword = isset($request['meta_keyword'][getDefaultLocaleId()])  ? $request['meta_keyword'][getDefaultLocaleId()] : $request['title'][getDefaultLocaleId()] ;
        $product->short_description = isset($request['short_description'][getDefaultLocaleId()])  ? $request['short_description'][getDefaultLocaleId()] : null ;
        $product->weight = isset($request['weight']) ? $request['weight'] : null;
        $product->length = isset($request['length']) ? $request['length'] : null;
        $product->height = isset($request['height']) ? $request['height'] : null;
        $product->width = isset($request['width']) ? $request['width'] : null;
        $product->volume = isset($request['volume']) ? $request['volume'] : null;
        $product->additional_information = isset($request['additional_information']) ? $request['additional_information'] : null;
    }
    
    /**
     * set product data from request
     *
     * @param array $request
     * @param Product $product
     * @return void
     */
    private function fromRequestToProductTrendyol(array $request, Product $product): void
    {
        $product->title = $request['title'];
        $product->type = $request['type'];
        $product->brand_id = $request['brand'];
        $product->store_id = $request['store'];
        $product->sku =  $request['sku'];
        $product->store_sku = $request['store_sku'];
        $product->price = isset($request['price']) ? $request['price'] : null;
        $product->promo_price = isset($request['promo_price']) ? $request['promo_price'] : null;
        $product->discount = isset($request['discount']) ? $request['discount'] : $request['price'] - $request['promo_price'];
        $product->discount_type = isset($request['discount_type']) ? $request['discount_type'] : 'fixed';
        $product->stock = isset($request['stock']) ? $request['stock'] : null;
        $product->free_delivery = 1;
        $product->data = $request['data'];
        $product->meta_title = isset($request['meta_title'])  ? $request['meta_title'] : $request['title'] ;
        $product->meta_desc = isset($request['meta_desc'])  ? $request['meta_desc'] : $request['data'] ;
        $product->meta_keyword = isset($request['meta_keyword'])  ? $request['meta_keyword'] : $request['title'] ;
        $product->short_description = isset($request['short_description'])  ? $request['short_description'] : null ;
        $product->volume = isset($request['volume']) ? $request['volume'] : null;
        $product->additional_information = isset($request['additional_information']) ? $request['additional_information'] : null;
        $product->barcode = $request['barcode'];
        $product->vat_rate = $request['vat_rate'];
//        $product->cargo_company_id = $request['cargo_company_id'];
        $product->stock_code = $request['stock_code'];
        $product->status = $request['status'];
        $product->trendyol_categories_id = isset($request['trendyol_categories_id']) ? $request['trendyol_categories_id'] : null;
        $product->trendyol_category = isset($request['trendyol_category']) ? $request['trendyol_category'] : null;
        
    }

    private function updateRequestToProductTrendyol(array $request, Product $product): void
    {
//        $product->title = $request['title'];
        $product->brand_id = $request['brand'];
        $product->sku =  $request['sku'];
        $product->store_sku = $request['store_sku'];
        $product->price = isset($request['price']) ? $request['price'] : null;
        $product->promo_price = isset($request['promo_price']) ? $request['promo_price'] : null;
        $product->discount = $request['price'] - $request['promo_price'];
        $product->discount_type = 'fixed';
        $product->stock = isset($request['stock']) ? $request['stock'] : null;
        $product->free_delivery = 1;
//        $product->data = $request['data'];
        $product->status = $request['status'];
//        $product->short_description = isset($request['short_description']) ? $request['short_description'] : $request['data'] ;
//        $product->meta_title = isset($request['meta_title']) ? $request['meta_title'] : $request['title'];
//        $product->meta_desc = isset($request['meta_desc']) ? $request['meta_desc'] : $request['data'];
        $product->volume = isset($request['volume']) ? $request['volume'] : null;
        $product->additional_information = isset($request['additional_information']) ? $request['additional_information'] : null;
        $product->trendyol_categories_id = isset($request['trendyol_categories_id']) ? $request['trendyol_categories_id'] : null;
        $product->trendyol_category = isset($request['trendyol_category']) ? $request['trendyol_category'] : null;
    }
    
    private function updateTrendyolProductStatus(array $request, Product $product): void
    {
        
        $product->price = isset($request['price']) ? $request['price'] : null;
        $product->promo_price = isset($request['promo_price']) ? $request['promo_price'] : null;
        $product->discount = $request['price'] - $request['promo_price'];
        $product->discount_type = 'fixed';
        $product->stock = isset($request['stock']) ? $request['stock'] : null;
        $product->status =  $request['status'];

    }
    
    private function updateRequestToProduct(array $request, Product $product): void
    {
        $product->title = $request['title'][getDefaultLocaleId()];
        if (isset($request['type'])) {
            $product->type = $request['type'];
        }
        $product->brand_id = $request['brand'];
        $product->store_id = $request['store'];
        $product->sku = $request['sku'];
        $product->store_sku = $request['store_sku'];
        $product->price = isset($request['price']) ? $request['price'] : null;
        $product->discount = $request['discount'];
        $product->discount_type = $request['discount_type'];
        $product->stock = isset($request['stock']) ? $request['stock'] : null;
        $product->free_delivery = 1;
        $product->data = $request['data'][getDefaultLocaleId()];
        $product->short_description = $request['short_description'][getDefaultLocaleId()] ? $request['short_description'][getDefaultLocaleId()] : null ;
        $product->meta_title = $request['meta_title'][getDefaultLocaleId()] ? $request['meta_title'][getDefaultLocaleId()] : $request['title'][getDefaultLocaleId()] ;
        $product->meta_desc = $request['meta_desc'][getDefaultLocaleId()] ? $request['meta_desc'][getDefaultLocaleId()] : $request['data'][getDefaultLocaleId()] ;
        $product->meta_keyword = $request['meta_keyword'][getDefaultLocaleId()] ? $request['meta_keyword'][getDefaultLocaleId()] : $request['title'][getDefaultLocaleId()] ;
        $product->weight = isset($request['weight']) ? $request['weight'] : null;
        $product->length = isset($request['length']) ? $request['length'] : null;
        $product->height = isset($request['height']) ? $request['height'] : null;
        $product->width = isset($request['width']) ? $request['width'] : null;
        $product->volume = isset($request['volume']) ? $request['volume'] : null;
        $product->additional_information = isset($request['additional_information']) ? $request['additional_information'] : null;
    }

    /**
     * set product translate data from request
     *
     * @param array $request
     * @param ProductTranslate $productTranslate
     * @return void
     */
    private function fromRequestToProductTranslate(array $request, Product $product)
    {
        foreach (getLocaleList() as $row) {
            $this->includeProductTranslateArr($request, $product, $row);
        }
    }
    
    

    private function includeProductTranslateArr(array $request, Product $product, $row)
    {
        $productTranslate = ProductTranslate::firstOrNew(['product_id' => $product->id, 'language_id' => $row->id]);
        $productTranslate->product_id = $product->id;
        $productTranslate->language_id = $row->id;
        $productTranslate->language_code = $row->code;
        $productTranslate->title = isset($request['title'][$row->id]) ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        $productTranslate->short_description = isset($request['short_description'][$row->id]) ? $request['short_description'][$row->id] : $request['short_description'][getDefaultLocaleId()];
        $productTranslate->data = $request['data'][$row->id] ? $request['data'][$row->id] : $request['data'][getDefaultLocaleId()];
        $productTranslate->meta_title = $request['meta_title'][$row->id] ? $request['meta_title'][$row->id] : $request['title'][getDefaultLocaleId()];
        $productTranslate->meta_desc = $request['meta_desc'][$row->id] ? $request['meta_desc'][$row->id] : $request['data'][getDefaultLocaleId()];
        $productTranslate->meta_keyword = $request['meta_keyword'][$row->id] ? $request['meta_keyword'][$row->id] : $request['title'][getDefaultLocaleId()];
        $productTranslate->status = true;
        $productTranslate->save();
    }
    
    
    private function fromRequestToProductTranslateTrendyol(array $request, Product $product)
    {
        foreach (getLocaleList() as $row) {
            $this->includeProductTranslateArrTrendyol($request, $product, $row);
        }
    }
    
    

    private function includeProductTranslateArrTrendyol(array $request, Product $product, $row)
    {
        $productTranslate = ProductTranslate::firstOrNew(['product_id' => $product->id, 'language_id' => $row->id]);
        $productTranslate->product_id = $product->id;
        $productTranslate->language_id = $row->id;
        $productTranslate->language_code = $row->code;
        $productTranslate->title =  $request['title'];
        $productTranslate->short_description =  $request['short_description'];
        $productTranslate->data =  $request['data'];
        $productTranslate->meta_title =  $request['title'];
        $productTranslate->meta_desc =  $request['data'];
        $productTranslate->meta_keyword =  $request['title'];
        $productTranslate->status = true;
        $productTranslate->save();
    }

    /**
     * update product translate data from request
     *
     * @param array $request
     * @param ProductTranslate $productTranslate
     * @return void
     */
    private function updateRequestToProductTranslate(array $request, Product $product)
    {
        foreach (getLocaleList() as $row) {
            $this->includeProductTranslateArr($request, $product, $row);
        }
    }
    
   
    
    /**
     * update product translate data from request
     *
     * @param array $request
     * @param ProductTranslate $productTranslate
     * @return void
     */
    private function updateRequestToProductTranslateTrendyol(array $request, Product $product)
    {
        foreach (getLocaleList() as $row) {
            $this->updateIncludeProductTranslateArrTrendyol($request, $product, $row);
        }
    }
    
     private function updateIncludeProductTranslateArrTrendyol(array $request, Product $product, $row)
    {
        $productTranslate = ProductTranslate::firstOrNew(['product_id' => $product->id, 'language_id' => $row->id]);
        $productTranslate->product_id = $product->id;
        $productTranslate->language_id = $row->id;
        $productTranslate->language_code = $row->code;
//        $productTranslate->title =  $request['title'];
//        $productTranslate->short_description =  $request['short_description'];
//        $productTranslate->data =  $request['data'];
//        $productTranslate->meta_title =  $request['title'];
//        $productTranslate->meta_desc =  $request['data'];
        $productTranslate->status = true;
        $productTranslate->save();
    }

    /**
     * save product tags
     *
     * @param Product $product
     * @param array $request
     * @param StoreStaff|null $seller
     * @return void
     */
    private function saveProductTags(Product $product, array $request, ?StoreStaff $seller)
    {
        $tags = [];
        if (isset($request['tags']) && !empty($request['tags'])) {
            $tags = $request['tags'];
        }

        $tags = $this->includeIsAdminTags($product, $tags, $seller);

        $product->tags()->sync($tags);
    }

    /**
     * include is_admin tags, if seller has logged in
     *
     * @param Product $product
     * @param array $tags
     * @param StoreStaff|null $seller
     * @return array
     */
    private function includeIsAdminTags(Product $product, array $tags, ?StoreStaff $seller): array
    {
        if (empty($seller)) {
            return $tags;
        }

        $admin_tags = $product->tags->filter(function ($r) {
            return $r->is_admin === 1;
        })->pluck('id')->toArray();

        $tags = array_merge($tags, $admin_tags);
        return $tags;
    }

    /**
     * delete product
     *
     * @param Product $product
     * @return void
     */
    public function delete(Product $product)
    {
        $attributes = ProductAttribute::whereProductId($product->id)->get();
        $this->deleteProductAttributes($attributes);
        Product::whereParentId($product->id)->delete();
        Media::delete($product->image);
        $this->deleteProductGallery($product->id);
        $product->tags()->sync([]);
        $product->categories()->sync([]);
        $product->delete();
    }

    /**
     * @param Product $product
     */
    public function forceDelete(Product $product)
    { 
        $attributes = ProductAttribute::whereProductId($product->id)->get();
        $this->deleteProductAttributes($attributes);
        Product::whereParentId($product->id)->delete();
        Media::delete($product->image);
        $this->deleteProductGallery($product->id);
        $product->tags()->sync([]);
        $product->categories()->sync([]);
        $product->forceDelete();
    }

    /**
     * delete product gallery
     *
     * @param int $product_id
     * @return void
     */
    private function deleteProductGallery(int $product_id)
    {
        $gallery = ProductGallery::whereProductId($product_id)->select('image')->get();
        if ($gallery->count() > 0) {

            // delete files from server
            foreach ($gallery as $r) {
                Media::delete($r->image);
            }

            // delete gallery from DB
            ProductGallery::whereProductId($product_id)->delete();
        }
    }

    /**
     * get product attribute option by id
     *
     * @param integer $data
     * @param integer $product_id
     * @return array
     */
    public function getProductAttributeOptionByID(int $data, int $product_id): array
    {
        $attributeTitle = [];
        if (isset($data) && !empty($data)) {
            $attributeTitle = ProductAttribute::whereAttributeId($data)
                ->whereProductId($product_id)
                ->pluck('option_id')->toArray();
        }

        return $attributeTitle;
    }

    public function import($filename)
    {
        // Excel::import(new ProductsImport(),request()->file('file'));
        // return (file(app_path()."/../tests/test-data/mot-products.xlsx"));
        $excel = Excel::import(new ProductsImport(), $filename);
    }

    /**
     * @param $row
     * @param $store
     * @return Product
     */
    public function saveViaCsv($rows, $store)
    {
        foreach ($rows as $row) {
            $productType = $this->getType($row['type']);
            if ($productType == Product::TYPE_CHILD) {
                continue;
            }

            /*get category row via categories string */
            $categoryService = new CategoryService();
//            $category = $categoryService->getByTitle($row['category']);
            $categories = explode('|', $row['category']);
            $categoriesIds = [];
            foreach ($categories as $categoryTitle) {
                $category = $categoryService->getByTitle($categoryTitle);
                if ($category != null) {
                    array_push($categoriesIds, $category->id);
                }
            }

            $product = Product::where('sku', $this->generateStoreSku($store->id, $row['sku']))->where('store_id', $store->id)->first();
            if (!$product) {
                $product = new Product();
            }
            $product = $this->saveProductRow($product, $row, $store);
            if ($productType == Product::TYPE_VARIABLE) {
                $childRows = $rows->where('parent_sku', $row['sku']);
                if ($childRows->count() <= 0) {
                    throw new \Exception('There is no variant of product ' . $product->title);
                }
                $this->saveProductChildsViaExcel($product, $childRows, $store);

                $product->price = $product->variations->last()->price;
                $product->promo_price = $product->variations->last()->discounted_price;
                $product->save();
            }

            if (count($categoriesIds) > 0) {
                $product->categories()->sync($categoriesIds);
            }

            ProductPriceUpdate::dispatch($product);
            ProductKeywordUpdate::dispatch($product);

        }
        $data = [
            'success' => true,
            'message' => __('Products has been saved successfully.')
        ];
    }

    /**
     * @param $type
     * @return string
     */
    private function getType($type)
    {
        $type = strtolower($type);
        switch ($type) {
            case Product::TYPE_VARIABLE:
                return Product::TYPE_VARIABLE;
                break;
            case Product::TYPE_BUNDLE:
                return Product::TYPE_BUNDLE;
                break;
            case Product::TYPE_CHILD:
                return Product::TYPE_CHILD;
                break;
            default:
                return Product::TYPE_SIMPLE;
        }
    }

    /**
     * @param $discountType
     * @return mixed
     */
    private function getDiscountType($discountType)
    {
        $discountType = strtolower($discountType);
        switch ($discountType) {
            case Product::FIXED:
                return Product::FIXED;
                break;
            default:
                return Product::PERCENTAGE;
        }
    }

    /**
     * @param $product
     * @param $row
     * @param $store
     * @return mixed
     */
    private function saveProductRow($product, $row, $store): Product
    {
        /*getting brand using brand slug*/
        $brandService = new BrandService();
        $brand = $row['brand'] != null ? $brandService->getByTitle($row['brand']) : null;
        /* get product type value */
        $productType = $this->getType($row['type']);
        /* saving product */
        $product->sku = $this->generateStoreSku($store->id, $row['sku']);
        $product->store_sku = $row['sku'];
        $product->title = $row['title'];
        $product->type = $productType;
        $product->brand_id = $brand != null ? $brand->id : null;
        $product->price = $row['price'];
        $product->discount_type = $row['discount_type'] != null ? $this->getDiscountType($row['discount_type']) : null;
        $product->discount = $row['discount'];
        $product->stock = $row['stock'];
        $product->free_delivery = 1;
        $product->data = $row['description'];
        $product->additional_information = $row['additional_information'] != null ? $row['additional_information'] : null;
        $product->meta_title = $row['meta_title'];
        $product->meta_desc = $row['meta_description'];
        $product->meta_keyword = $row['meta_keyword'];
        $product->store_id = $store->id;
        $product->is_approved = false;
        $product->short_description = $row['short_description'] ?? null;
        if ($productType != Product::TYPE_VARIABLE) {
            $product->width = $row['width'] ?? null;
            $product->height = $row['height'] ?? null;
            $product->length = $row['length'] ?? null;
            $product->weight = $row['weight'] ?? null;
            $product->volume = $row['volume'] ?? null;
        }

        $createdBy = Product::ADMIN;
        if(\Auth::guard('seller')->check()) {
            $createdBy = Product::SELLER;
        }
        $product->created_by = $createdBy;
        $product->created_by_id = auth()->user()->id;
        $product->save();
        $product->generateSlug();
        /*set promo price*/
        $product->promo_price = $product->discounted_price;
        $product->promo_source_id = $product->id;
        $product->promo_source_type = get_class($product);
        if (isset($row['image']) && $row['image'] != null) {
            $fileName = $row['image'];
            $product->image = $this->moveImportToProducts($fileName, $store);
        }
        $product->save();

        if (isset($row['gallery_images']) && $row['gallery_images'] != null) {
            $images = explode(',', $row['gallery_images']);
            if (count($images) > 0) {
                ProductGallery::where('product_id', $product->id)->delete();
                $i = 1;
                foreach ($images as $image) {
                    if ($image == null) {
                        continue;
                    }
                    $productGallery = new ProductGallery();
                    $productGallery->product_id = $product->id;
                    $productGallery->image = $this->moveGalleryImportToProducts($image, $store);
                    $productGallery->sort_order = $i;
                    $productGallery->save();
                    $i++;
                }
            }
        }

        return $product;
    }

    /**
     * @param $product
     * @param $childRows
     */
    private function saveProductChildsViaExcel($product, $childRows, $store)
    {
        ProductAttribute::where('product_id', $product->id)->delete();
        foreach ($childRows as $key => $row) {

            $sku = $this->generateStoreSku($product->store_id, $row['sku']);
            $variation = Product::where('sku', $sku)->whereNotNull('parent_id')->first();
            if (!$variation) {
                $variation = new Product();
            }
            $variation->sku = $sku;
            $variation->store_sku = $row['sku'];
            $variation->parent_id = $product->id;
            $variation->type = Product::TYPE_VARIATION;
            $variation->brand_id = $product->brand_id;
            $variation->price = $row['price'];
            $variation->discount_type = $row['discount_type'] != null ? $this->getDiscountType($row['discount_type']) : null;
            $variation->discount = $row['discount'];
            $variation->stock = $row['stock'];
            $variation->free_delivery = 1;
            $variation->store_id = $product->store_id;
            $variation->short_description = $row['short_description'] ?? null;
            $variation->width = $row['width'] ?? null;
            $variation->height = $row['height'] ?? null;
            $variation->length = $row['length'] ?? null;
            $variation->weight = $row['weight'] ?? null;
            $variation->volume = $row['volume'] ?? null;
            if (isset($row['image']) && $row['image'] != null) {
                $fileName = $row['image'];
                $variation->image = $this->moveImportToProducts($fileName, $store);
            }
            $variation->save();
            $variation->slug = $product->slug;
            $variation->promo_price = $variation->discounted_price;
            $variation->promo_source_id = $variation->id;
            $variation->promo_source_type = get_class($variation);
            $variation->save();

            $this->saveExcelProductAttributes($product, $variation, $row);
        }
    }

    /**
     * @param $product
     * @param $variationProduct
     * @param $excelChildRow
     */
    private function saveExcelProductAttributes($product, $variationProduct, $excelChildRow)
    {
        $excelChildRow = $excelChildRow->toarray();
        $attrWithValues = array_filter($excelChildRow, function ($v) use ($excelChildRow) {
            return preg_match('#attribute_\d#', array_search($v, $excelChildRow));
        });

        $errorMessages = [];
        $count = 0;
        foreach ($attrWithValues as $attrWithValue) {
            $attributeService = new AttributeService();
            $attribute = explode(':', $attrWithValue)[0];
            $option = explode(':', $attrWithValue)[1];

            $attributeRow = $attributeService->getAttributeByTitle($attribute);
            $optionRow = $attributeService->getOptionByTitle($option);

            if ($attributeRow == null || $optionRow == null) {
                $errorMessages['messages'] = "There are some missing attribute or attribute value";
                continue;
            }

            $productAttribute = new ProductAttribute();
            $productAttribute->product_id = $product->id;
            $productAttribute->variation_id = $variationProduct->id;
            $productAttribute->attribute_id = $attributeRow->id;
            $productAttribute->option_id = $optionRow->id;

            $productAttribute->save();
        }
        $count++;
    }

    private function moveImportToProducts($fileName, $store)
    {
        $fromPath = public_path('storage/imports/').$fileName;
        $base_path = public_path('storage/original/');
        $image_path = $store->slug.'/products/'.date('Y').'/'.date('m').'/';
        $formatedFileName = $store->slug.'-'.$fileName;
        $toPath = $base_path.$image_path.$formatedFileName;

        $toCopyBasePath = public_path('storage/product_listing/');
        $toCopyPath = $toCopyBasePath . $image_path . $formatedFileName;
        /* copy file to product listing */
        if (!file_exists($toCopyBasePath . $image_path)) {
            \File::makeDirectory($toCopyBasePath . $image_path, 0777, true);
        }
        /* move file to original folder */
        if (!file_exists($base_path . $image_path)) {
            \File::makeDirectory($base_path . $image_path, 0777, true);
        }
        $base_path_product_detail = public_path('storage/product_detail/');
        if(!file_exists($base_path_product_detail.$image_path)){
            \File::makeDirectory($base_path_product_detail.$image_path, 0777, true);
        }
        $base_path_product_thumbnail = public_path('storage/product_thumbnail/');
        if(!file_exists($base_path_product_thumbnail.$image_path)){
            \File::makeDirectory($base_path_product_thumbnail.$image_path, 0777, true);
        }
        $base_path_thumbnail = public_path('storage/thumbnail/');
        if(!file_exists($base_path_thumbnail.$image_path)){
            \File::makeDirectory($base_path_thumbnail.$image_path, 0777, true);
        }

        if (file_exists($fromPath)) {
            copy($fromPath, $toCopyPath);
            \File::move($fromPath, $toPath);
            ResizeImageProcess::dispatch($formatedFileName, 'product');
        }

        return $image_path.$formatedFileName;
    }

    private function moveGalleryImportToProducts($fileName, $store)
    {
        $fromPath = public_path('storage/imports/').$fileName;
        $base_path = public_path('storage/original/');
        $image_path = $store->slug.'/products/'.date('Y').'/'.date('m').'/';
        $toPath = $base_path.$image_path.'moved-'.$fileName;

        if(!file_exists($base_path.$image_path)){
            \File::makeDirectory($base_path.$image_path, 0777, true);
        }
        $base_path_product_listing = public_path('storage/product_listing/');
        if(!file_exists($base_path_product_listing.$image_path)){
            \File::makeDirectory($base_path_product_listing.$image_path, 0777, true);
        }
        $base_path_product_detail = public_path('storage/product_detail/');
        if(!file_exists($base_path_product_detail.$image_path)){
            \File::makeDirectory($base_path_product_detail.$image_path, 0777, true);
        }
        $base_path_product_thumbnail = public_path('storage/product_thumbnail/');
        if(!file_exists($base_path_product_thumbnail.$image_path)){
            \File::makeDirectory($base_path_product_thumbnail.$image_path, 0777, true);
        }
        $base_path_thumbnail = public_path('storage/thumbnail/');
        if(!file_exists($base_path_thumbnail.$image_path)){
            \File::makeDirectory($base_path_thumbnail.$image_path, 0777, true);
        }
        if(file_exists($fromPath)){
            \File::move($fromPath, $toPath);

            ResizeImageProcess::dispatch('/'.$image_path.'moved-'.$fileName, 'product');
        }

        return $image_path.'moved-'.$fileName;
    }

    /**
     * @param $store_id
     * @param $sku
     * @return string
     */
    public function generateStoreSku($store_id, $sku)
    {
        $storeCode = makeSkuCode($store_id);
        return $storeCode.$store_id.'-'.$sku;
    }

    public function deleteProductAttributes($attributes)
    {
        foreach ($attributes as $attribute) {
            $orderItems = OrderItem::where('product_id', $attribute->variation_id)->get();
            if ($orderItems->count() == 0) {
                $attribute->forceDelete();
                continue;
            }
            $attribute->delete();
        }
    }
}
