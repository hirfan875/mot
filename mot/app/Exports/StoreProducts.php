<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductTranslate;
use App\Service\FilterProductsService;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Excel;
use App\Service\CategoryService;
use App\Service\BrandService;
use App\Service\AttributeService;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class StoreProducts implements WithEvents
{
    use RegistersEventListeners;

    private static $store;

    public function __construct($store)
    {
        self::$store = $store;
    }

    /**
     * @param BeforeWriting $event
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    /* load existing sheet from assets folder and create new sheet with updated categories, brands and attributes */
    public static function beforeWriting(BeforeWriting $event)
    {
//        $templateFile = new LocalTemporaryFile(public_path('assets/backend/sample/mot-products.xlsx'));
        $templateFile = new LocalTemporaryFile(public_path('assets/backend/sample/mot-products.xlsm'));
        $event->writer->reopen($templateFile,  Excel::XLSX);

        $productsSheet = $event->writer->getSheetByIndex(0);
        $attributesSheet = $event->writer->getSheetByIndex(1);
        $categoriesSheet = $event->writer->getSheetByIndex(2);
        $brandsSheet = $event->writer->getSheetByIndex(3);

        (new StoreProducts(self::$store))->populateAttributesSheet($attributesSheet);
        (new StoreProducts(self::$store))->populateCategoriesSheet($categoriesSheet);
        (new StoreProducts(self::$store))->populateBrandsSheet($brandsSheet);
        (new StoreProducts(self::$store))->populateSampleProductsSheet($productsSheet, $event);

        $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first(products) sheet
        $event->writer->getSheetByIndex(1)->export($event->getConcernable()); // call the export on the second(attributes) sheet
        $event->writer->getSheetByIndex(2)->export($event->getConcernable()); // call the export on the third(categories) sheet
        $event->writer->getSheetByIndex(3)->export($event->getConcernable()); // call the export on the fourth(brands) sheet

        $event->getWriter()->getSheetByIndex(0);
        $event->getWriter()->getSheetByIndex(1);
        $event->getWriter()->getSheetByIndex(2);
        $event->getWriter()->getSheetByIndex(3);
    }

    /**
     * @param $attributesSheet
     */
    private function populateAttributesSheet($attributesSheet)
    {
        $attributeService = new AttributeService();
        $attributes = $attributeService->getAllGroupedByTitle();
        $iteration = 2;
        $i=2;
        foreach ($attributes as $attribute => $options) {
            $A = "A" . ($iteration);
            $B = "B" . ($iteration);
            $C = "C" . ($iteration);

            $attributesSheet->setCellValue($A, $attribute); //print all attributes list on first column
            if ($options->count() > 0) {
                foreach ($options[0]->options as $option) {
                    if ($option->parent_id != null) {
                        $B = "B" . ($i);
                        $C = "C" . ($i);
                        $attributesSheet->setCellValue($B, $option->title); //print only options list on second column
                        $attributesSheet->setCellValue($C, $attribute . ':' . $option->title); //print all options with attribute on third column
                        $i++;
                    }
                }
            }
            $iteration++;
        }
    }

    /**
     * @param $categoriesSheet
     */
    private function populateCategoriesSheet($categoriesSheet)
    {
        $categoryService = new CategoryService();
        $categories = $categoryService->getAll();
        $iteration = 2;

        foreach ($categories as $category) {
            $A = "A" . ($iteration);
            $categoriesSheet->setCellValue($A, $category->title);
            $iteration++;
        }
    }

    /**
     * @param $brandsSheet
     */
    private function populateBrandsSheet($brandsSheet)
    {
//        $brandService = new BrandService();
        $brands = Brand::where('is_approved' , 1)->whereStatus(true)->get();
        $iteration = 2;

        foreach ($brands as $brand) {
            $A = "A" . ($iteration);
            $brandsSheet->setCellValue($A, $brand->title);

            $iteration++;
        }
    }

    /**
     * @param $productsSheet
     */
    public function populateSampleProductsSheet($productsSheet)
    {
        // Type Category
        $category_column = 'E';
        $formula = 'Categories!$A$2:$A$500'; //select dropdown options range for categories
        $this->makeDropdownColumn($productsSheet, $category_column, null, $formula);

        // Type Brands
        $brand_column = 'F';
        $formula = 'Brands!$A$2:$A$500'; //select dropdown options range for brands
        $this->makeDropdownColumn($productsSheet, $brand_column, null, $formula);

        // Type Attributes
        $brand_columns = ['L', 'M', 'N', 'O']; //all attributes column
        $formula = 'Attributes!$C$2:$C$500'; //select dropdown options range for attributes
        foreach ($brand_columns as $column) {
            $this->makeDropdownColumn($productsSheet, $column, null, $formula);
        }

        // Type Column
        $type_column = 'A';
        $type_options = [
            'Simple',
            'Variable',
            'Child',
        ];
        $this->makeDropdownColumn($productsSheet, $type_column, $type_options);

        // Discount Types Column
        $discount_type_column = 'I';
        $discount_type_options = [
            'Fixed',
            'Percentage',
        ];
        $this->makeDropdownColumn($productsSheet, $discount_type_column, $discount_type_options);

        // Tags Column
        $tags_column = 'G';
        $tags_options = [
            'Top',
            'Trending',
            'Featured',
        ];
        $this->makeDropdownColumn($productsSheet, $tags_column, $tags_options);

        $iteration = 2;
        $filterProductsService = new FilterProductsService();
        $products = $filterProductsService->byStore(Self::$store->id)->get();

        foreach ($products as $product) {
            $A = "A" . ($iteration); //type
            $B = "B" . ($iteration); //parent_sku
            $C = "C" . ($iteration); //sku
            $D = "D" . ($iteration); //title
            $E = "E" . ($iteration); //category
            $F = "F" . ($iteration); //brand
            $G = "G" . ($iteration); //tags
            $H = "H" . ($iteration); //price
            $I = "I" . ($iteration); //discount type
            $J = "J" . ($iteration); //discount
            $K = "K" . ($iteration); //stock
            $L = "L" . ($iteration); //attribute 1
            $M = "M" . ($iteration); //attribute 2
            $N = "N" . ($iteration); //attribute 3
            $O = "O" . ($iteration); //attribute 4
            $P = "P" . ($iteration); //weight
            $Q = "Q" . ($iteration); //length
            $R = "R" . ($iteration); //height
            $S = "S" . ($iteration); //width
            $T = "T" . ($iteration); //volume
            $U = "U" . ($iteration); //short description
            $V = "V" . ($iteration); //description
            $W = "W" . ($iteration); //additional information
            $X = "X" . ($iteration); //meta title
            $Y = "Y" . ($iteration); //meta description
            $Z = "Z" . ($iteration); //meta keyword
            $AA = "AA" . ($iteration); //image
            $AB = "AB" . ($iteration); //image gallery
            /* arabic translation */
            $AC = "AC" . ($iteration); //arabic title
            $AD = "AD" . ($iteration); //arabic short description
            $AE = "AE" . ($iteration); //arabic description
            $AF = "AF" . ($iteration); //arabic meta title
            $AG = "AG" . ($iteration); //arabic meta description
            $AH = "AH" . ($iteration); //arabic meta keyword
            /* turkish translation */
            $AI = "AI" . ($iteration); //turkish title
            $AJ = "AJ" . ($iteration); //turkish short description
            $AK = "AK" . ($iteration); //turkish description
            $AL = "AL" . ($iteration); //turkish meta title
            $AM = "AM" . ($iteration); //turkish meta description
            $AN = "AN" . ($iteration); //turkish meta keyword

            $productsSheet->setCellValue($A, $this->getTypeName($product));
            $productsSheet->setCellValue($B, null);
            $productsSheet->setCellValue($C, $product->store_sku);
            $productsSheet->setCellValue($D, $product->title);
            $productsSheet->setCellValue($E, implode("|", $product->categories->pluck('title')->toArray()) );
            $productsSheet->setCellValue($F, $product->brand != null ? $product->brand->title : null);
            $productsSheet->setCellValue($G, null); //TODO will be dynamic soon
            $productsSheet->setCellValue($H, $product->price);
            $productsSheet->setCellValue($I, $product->discunt_type != null ? ucfirst($product->discount_type) : null);
            $productsSheet->setCellValue($J, $product->discount);
            $productsSheet->setCellValue($K, $product->stock);
            $productsSheet->setCellValue($L, null);
            $productsSheet->setCellValue($M, null);
            $productsSheet->setCellValue($N, null);
            $productsSheet->setCellValue($O, null);
            $productsSheet->setCellValue($P, $product->weight);
            $productsSheet->setCellValue($Q, $product->length);
            $productsSheet->setCellValue($R, $product->height);
            $productsSheet->setCellValue($S, $product->width);
            $productsSheet->setCellValue($T, $product->volume);
            $productsSheet->setCellValue($U, $product->product_translates ? $product->product_translates->short_description : $product->short_description);
            $productsSheet->setCellValue($V, $product->product_translates ? $product->product_translates->data : $product->data);
            $productsSheet->setCellValue($W, $product->additional_information);
            $productsSheet->setCellValue($X, $product->product_translates ? $product->product_translates->meta_title : $product->meta_title);
            $productsSheet->setCellValue($Y, $product->product_translates ? $product->product_translates->meta_desc : $product->meta_desc);
            $metaKeyword = $product->product_translates ? $product->product_translates->meta_keyword : $product->meta_keyword;
            if($metaKeyword != null){
                $metaKeyword = explode("--", $metaKeyword);
                $metaKeyword = $metaKeyword[0];
            }
            $productsSheet->setCellValue($Z, $metaKeyword);
            /*arabic translation row*/
            $arabicRow = ProductTranslate::where(['product_id' => $product->id, 'language_code' => 'ar'])->first();
            $productsSheet->setCellValue($AC, $arabicRow != null ? $arabicRow->title : null);
            $productsSheet->setCellValue($AD, $arabicRow != null ? $arabicRow->short_description : null);
            $productsSheet->setCellValue($AE, $arabicRow != null ? $arabicRow->data : null);
            $productsSheet->setCellValue($AF, $arabicRow != null ? $arabicRow->meta_title : null);
            $productsSheet->setCellValue($AG, $arabicRow != null ? $arabicRow->meta_desc : null);
            $productsSheet->setCellValue($AH, $arabicRow != null ? $arabicRow->meta_keyword : null);

            /*turkish translation row*/
            $turkishRow = ProductTranslate::where(['product_id' => $product->id, 'language_code' => 'tr'])->first();
            $productsSheet->setCellValue($AI, $turkishRow != null ? $turkishRow->title : null);
            $productsSheet->setCellValue($AJ, $turkishRow != null ? $turkishRow->short_description : null);
            $productsSheet->setCellValue($AK, $turkishRow != null ? $turkishRow->data : null);
            $productsSheet->setCellValue($AL, $turkishRow != null ? $turkishRow->meta_title : null);
            $productsSheet->setCellValue($AM, $turkishRow != null ? $turkishRow->meta_desc : null);
            $productsSheet->setCellValue($AN, $turkishRow != null ? $turkishRow->meta_keyword : null);


            $iteration++;
        }
    }

    /**
     * @param $sheet
     * @param $drop_column
     * @param $options
     * @param null $formula
     */
    private function makeDropdownColumn($sheet, $drop_column, $options, $formula = null)
    {
        if ($options != null) {
            $formula = sprintf('"%s"', implode(',', $options));
        }

        $row_count = 1000;
        // set dropdown list for first data row
        $validation = $sheet->getCell("{$drop_column}2")->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Value is not in list.');
        $validation->setPromptTitle('Pick from list');
        $validation->setPrompt('Please pick a value from the dropdown list.');
        $validation->setFormula1($formula);

        // clone validation to remaining rows
        for ($i = 3; $i <= $row_count; $i++) {
            $sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
        }
    }

    /**
     * @param $product
     * @return string|null
     */
    private function getTypeName($product)
    {
        $type = null;
        if ($product->type == "simple") {
            $type = "Simple";
        }
        if ($product->type == "variation") {
            $type = "Child";
        }
        if ($product->type == "variation" && $product->parent_id == null) {
            $type = "Variable";
        }

        return $type;
    }
}
