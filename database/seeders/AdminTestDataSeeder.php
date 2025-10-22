<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Sembrando datos de prueba...');

        // Obtener usuarios existentes
        $admin = User::where('role', 'admin')->first();
        $client = User::where('role', 'client')->first();

        // Crear categorías
        $categories = [
            [
                'name' => 'Suplementos',
                'slug' => 'suplementos',
                'description' => 'Suplementos nutricionales y vitaminas',
                'is_active' => true,
            ],
            [
                'name' => 'Proteínas',
                'slug' => 'proteinas',
                'description' => 'Proteínas en polvo y batidos',
                'is_active' => true,
            ],
            [
                'name' => 'Alimentos Orgánicos',
                'slug' => 'alimentos-organicos',
                'description' => 'Alimentos orgánicos y naturales',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
            $this->command->info("✅ Categoría creada: {$category->name}");
        }

        // Crear productos
        $products = [
            [
                'category_id' => Category::where('slug', 'suplementos')->first()->id,
                'name' => 'Multivitamínico Completo',
                'slug' => 'multivitaminico-completo',
                'description' => 'Complejo multivitamínico con 24 vitaminas y minerales esenciales.',
                'price' => 29.99,
                'compare_price' => 39.99,
                'stock' => 50,
                'sku' => 'SUP-001',
                'is_active' => true,
                'is_featured' => true,
                'benefits' => 'Mejora el sistema inmunológico, aumenta la energía, apoya la salud general',
                'ingredients' => 'Vitamina A, C, D, E, K, B-Complex, Zinc, Magnesio',
                'weight' => '60 cápsulas',
            ],
            [
                'category_id' => Category::where('slug', 'proteinas')->first()->id,
                'name' => 'Proteína Whey Premium',
                'slug' => 'proteina-whey-premium',
                'description' => 'Proteína de suero de alta calidad para desarrollo muscular.',
                'price' => 49.99,
                'stock' => 30,
                'sku' => 'PROT-001',
                'is_active' => true,
                'is_featured' => true,
                'benefits' => 'Desarrollo muscular, recuperación post-entrenamiento',
                'ingredients' => '100% Whey Protein Isolate',
                'weight' => '2kg',
            ],
            [
                'category_id' => Category::where('slug', 'alimentos-organicos')->first()->id,
                'name' => 'Quinoa Orgánica',
                'slug' => 'quinoa-organica',
                'description' => 'Quinoa orgánica de alta calidad, libre de pesticidas.',
                'price' => 12.99,
                'stock' => 100,
                'sku' => 'ORG-001',
                'is_active' => true,
                'benefits' => 'Alta en proteína vegetal, sin gluten, rica en fibra',
                'ingredients' => '100% Quinoa Orgánica',
                'weight' => '500g',
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
            $this->command->info("✅ Producto creado: {$product->name}");
        }

        // Crear reseñas si hay cliente
        if ($client) {
            $product1 = Product::where('slug', 'multivitaminico-completo')->first();
            $product2 = Product::where('slug', 'proteina-whey-premium')->first();

            $reviews = [
                [
                    'product_id' => $product1->id,
                    'user_id' => $client->id,
                    'rating' => 5,
                    'title' => 'Excelente producto',
                    'comment' => 'Me encanta este multivitamínico, he notado una gran diferencia en mi energía diaria.',
                    'is_approved' => true,
                ],
                [
                    'product_id' => $product2->id,
                    'user_id' => $client->id,
                    'rating' => 4,
                    'title' => 'Buena proteína',
                    'comment' => 'Buen sabor y se mezcla bien. Recomendada.',
                    'is_approved' => false,
                ],
            ];

            foreach ($reviews as $reviewData) {
                Review::firstOrCreate(
                    [
                        'product_id' => $reviewData['product_id'],
                        'user_id' => $reviewData['user_id'],
                    ],
                    $reviewData
                );
            }
            $this->command->info("✅ Reseñas creadas");
        }

        // Crear un pedido de ejemplo si hay cliente
        if ($client) {
            $product = Product::first();
            
            $order = Order::create([
                'user_id' => $client->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => 29.99,
                'tax' => 4.80,
                'shipping' => 5.00,
                'total' => 39.79,
                'payment_method' => 'card',
                'payment_status' => 'pending',
                'shipping_name' => $client->name,
                'shipping_email' => $client->email,
                'shipping_phone' => '555-1234',
                'shipping_address' => 'Calle Principal 123',
                'shipping_city' => 'Ciudad de México',
                'shipping_state' => 'CDMX',
                'shipping_zipcode' => '06000',
                'shipping_country' => 'México',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
                'subtotal' => $product->price,
            ]);

            $this->command->info("✅ Pedido creado: {$order->order_number}");
        }

        $this->command->newLine();
        $this->command->info('🎉 ¡Datos de prueba creados exitosamente!');
        $this->command->newLine();
        $this->command->info('📊 Resumen:');
        $this->command->info('   • ' . Category::count() . ' categorías');
        $this->command->info('   • ' . Product::count() . ' productos');
        $this->command->info('   • ' . Review::count() . ' reseñas');
        $this->command->info('   • ' . Order::count() . ' pedidos');
    }
}

