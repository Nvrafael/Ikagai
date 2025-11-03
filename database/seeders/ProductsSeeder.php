<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('No hay categorías. Crea categorías primero.');
            return;
        }

        $products = [
            [
                'name' => 'Proteína de Suero de Leche',
                'description' => 'Suplemento proteico de alta calidad con 25g de proteína por porción',
                'price' => 59.99,
                'compare_price' => 69.99,
                'stock' => 50,
                'sku' => 'PROT-WHY-001',
                'benefits' => 'Ayuda a construir y reparar músculos, ideal para deportistas',
                'ingredients' => 'Proteína de suero de leche, cacao, stevia',
                'weight' => '900g',
                'is_featured' => true,
            ],
            [
                'name' => 'Batido Verde Natural',
                'description' => 'Batido en polvo con superfoods como espirulina, chlorella y matcha',
                'price' => 39.99,
                'compare_price' => 49.99,
                'stock' => 30,
                'sku' => 'BAT-GRN-001',
                'benefits' => 'Fuente natural de antioxidantes y energía',
                'ingredients' => 'Espirulina, chlorella, matcha, stevia',
                'weight' => '500g',
                'is_featured' => true,
            ],
            [
                'name' => 'Aceite de Coco Virgen',
                'description' => 'Aceite de coco prensado en frío, 100% orgánico',
                'price' => 24.99,
                'compare_price' => null,
                'stock' => 40,
                'sku' => 'ACE-ORG-001',
                'benefits' => 'Ideal para cocción y cuidado personal',
                'ingredients' => 'Aceite de coco 100% puro',
                'weight' => '500ml',
                'is_featured' => false,
            ],
            [
                'name' => 'Té Verde Premium',
                'description' => 'Té verde ecológico en hebras de máxima calidad',
                'price' => 18.99,
                'compare_price' => 22.99,
                'stock' => 60,
                'sku' => 'TEA-GRN-001',
                'benefits' => 'Rico en antioxidantes, ayuda a acelerar el metabolismo',
                'ingredients' => 'Hojas de té verde 100% orgánicas',
                'weight' => '100g',
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            Product::create(array_merge($productData, [
                'category_id' => $categories->random()->id,
                'slug' => Str::slug($productData['name']),
                'is_active' => true,
            ]));
        }

        $this->command->info('✅ ' . count($products) . ' productos creados exitosamente!');
    }
}

