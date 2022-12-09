<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Service\AttributeService;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title' => 'Color',
                'type' => 'colors',
                'options' => [
                    [
                        'title' => 'Black',
                        'code' => '#000000'
                    ],
                    [
                        'title' => 'Red',
                        'code' => '#FF0000'
                    ],
                    [
                        'title' => 'Blue',
                        'code' => '#0000FF'
                    ]
                ]
            ],
            [
                'title' => 'Size',
                'type' => 'swatches',
                'options' => [
                    [
                        'title' => 'Small'
                    ],
                    [
                        'title' => 'Medium'
                    ],
                    [
                        'title' => 'Large'
                    ]
                ]
            ]
        ];

        $attributeService = new AttributeService();
        foreach ($data as $r) {
            $attribute = $attributeService->create($r);

            foreach ($r['options'] as $option) {
                $attributeService->create($option, $attribute->id);
            }
        }
    }
}
