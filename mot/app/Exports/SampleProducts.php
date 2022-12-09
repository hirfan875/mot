<?php

namespace App\Exports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Excel;
use App\Service\CategoryService;
use App\Service\BrandService;
use App\Service\AttributeService;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class SampleProducts implements WithEvents
{
    use RegistersEventListeners;

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

        (new SampleProducts())->populateAttributesSheet($attributesSheet);
        (new SampleProducts())->populateCategoriesSheet($categoriesSheet);
        (new SampleProducts())->populateBrandsSheet($brandsSheet);
        (new SampleProducts())->populateSampleProductsSheet($productsSheet, $event);

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

            /*$cellRangeTarget = $A.':'.$A;
             Copy style of Row 2 onto new rows - RowHeight is not being copied, need to adjust manually...
            if($iteration > 2)
            {
                $categoriesSheet->duplicateStyle($categoriesSheet->getStyle('A3'), $cellRangeTarget);
            }*/

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
}
