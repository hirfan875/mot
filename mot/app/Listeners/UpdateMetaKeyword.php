<?php

namespace App\Listeners;

use App\Events\ProductKeywordUpdate;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Monolog\Logger;
use App\Helpers\UtilityHelpers;

class UpdateMetaKeyword implements ShouldQueue
{
    /** @var float */
    // protected $price;

    /** @var string */
    // protected $discount_source;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'update-keyword-queue';

    /**
     * Handle the event.
     *
     * @param  ProductKeywordUpdate  $event
     * @return void
     */
    public function handle(ProductKeywordUpdate $event)
    {
        $logger = getLogger('update-keyword-queue');

        $product = $event->product;
        $brand = isset($product->brand) ? $product->brand->slug : '' ; //get brand slug string
        $store = $product->store->slug; //get store slug string
        $commonWords = UtilityHelpers::getCommonWordsArray();
        $categoriesArray = $product->categories->pluck('slug')->toArray(); //get all categories slug array
        $categoriesString = implode('-', $categoriesArray); //convert category array to "-" separated string
        $oldMetaKeyword = $product->meta_keyword;

        if($oldMetaKeyword != null) {
            $prevKeywords = explode('--', $product->meta_keyword);
            $oldMetaKeyword    = $prevKeywords[0]; //get user defined keywords
        }

        $mergedString = sprintf("%s-%s-%s", $brand, $store, $categoriesString); //merging string
        $metaKeywordStr = $this->getFormatedKeywords($mergedString, $commonWords);

        $product->meta_keyword = $oldMetaKeyword.'--'.$metaKeywordStr;
        $product->save();
    }

    /**
     * Get the comma sepatared keywords.
     *
     * @param  string $mergedString, common words array $commonWords
     * @return string
     */
    protected function getFormatedKeywords($mergedString, $commonWords){
        $convertedArray = explode('-', $mergedString); //convert string to array
        $convertedArray = array_diff($convertedArray, $commonWords); //removing words from an array
        $convertedArray = array_unique($convertedArray);//remove unique values
        $metaKeywords = implode(',', $convertedArray); //creating comma separated string

        return $metaKeywords;
    }

}
