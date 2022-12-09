<?php

namespace App\Imports;

use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Service\ProductService;
use App\Models\Store;

class ProductsImport implements
    ToCollection,
    WithHeadingRow,
    SkipsOnError,
    SkipsEmptyRows,
    WithMultipleSheets,
    WithValidation
{
    use Importable, SkipsErrors;
    public $store;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $rows)
    {
        /*$validator = Validator::make($rows->toArray(), [
            '*.type' => 'required',
            '*.sku' => 'required',
            '*.parent_sku' => 'required_if:*.type,==,Child',
            '*.discount_type' => 'required_with:*.discount',
            '*.discount' => 'required_with:*.discount_type',
            '*.category' => 'required_if:*.type,==,Variable,Simple',
            '*.brand' => 'required_if:*.type,==,Variable,Simple',
            '*.price' => 'required_if:*.type,==,Child,Simple',
            '*.title' => 'required_if:*.type,==,Variable,Simple',

    ])->validate();*/
        $productService = new ProductService();
        $product = $productService->saveViaCsv($rows, $this->store);
    }

    public function rules(): array
    {
        return [
            '*.type' => 'required',
            '*.sku' => 'required',
            '*.title' => 'required_if:*.type,==,Variable,Simple',
            '*.parent_sku' => 'required_if:*.type,==,Child',
            '*.discount_type' => 'required_with:*.discount',
            '*.discount' => ['required_with:*.discount_type', 'nullable', 'numeric', 'min:0.1','max:99.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            '*.category' => 'required_if:*.type,==,Variable,Simple',
//            '*.brand' => 'required_if:*.type,==,Variable,Simple',
            '*.price' => ['required_if:*.type,==,Child,Simple', 'nullable', 'numeric', 'min:0.1', 'regex:/^\d+(\.\d{1,2})?$/'],
            '*.attribute_1' => 'required_if:*.type,==,Child',
            '*.weight' => 'nullable|numeric',
            '*.length' => 'nullable|numeric',
            '*.height' => 'nullable|numeric',
            '*.width' => 'nullable|numeric',
            '*.volume' => 'nullable|numeric',
        ];
    }

    /**
     * @return array
     */
    /*public function customValidationMessages()
    {
        return [
            'parent_sku.required' => ':attribute is required.',
        ];
    }*/
}
