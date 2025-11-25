<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear datos de prueba
        $category = Category::create([
            'name' => 'Proteínas',
            'slug' => 'proteinas',
            'description' => 'Suplementos proteicos',
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Proteína Whey',
            'slug' => 'proteina-whey',
            'description' => 'Proteína de suero de alta calidad',
            'price' => 599.99,
            'stock' => 50,
            'is_active' => true,
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Creatina Monohidrato',
            'slug' => 'creatina-monohidrato',
            'description' => 'Creatina pura',
            'price' => 299.99,
            'stock' => 100,
            'is_active' => true,
            'is_featured' => false,
        ]);
    }

    /** @test */
    public function puede_listar_productos()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'description',
                            'price',
                            'stock',
                            'category',
                            'average_rating',
                            'reviews_count',
                        ]
                    ],
                    'meta' => [
                        'current_page',
                        'last_page',
                        'per_page',
                        'total',
                    ],
                ])
                ->assertJson([
                    'success' => true,
                ]);

        $this->assertEquals(2, count($response->json('data')));
    }

    /** @test */
    public function puede_buscar_productos_por_nombre()
    {
        $response = $this->getJson('/api/products?search=whey');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.name', 'Proteína Whey');
    }

    /** @test */
    public function puede_filtrar_productos_por_categoria()
    {
        $category = Category::first();

        $response = $this->getJson("/api/products?category_id={$category->id}");

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function puede_filtrar_productos_por_precio()
    {
        $response = $this->getJson('/api/products?min_price=400&max_price=700');

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.name', 'Proteína Whey');
    }

    /** @test */
    public function puede_ordenar_productos()
    {
        $response = $this->getJson('/api/products?sort_by=price_asc');

        $response->assertStatus(200);
        
        $prices = collect($response->json('data'))->pluck('price');
        $this->assertEquals($prices->sort()->values()->all(), $prices->values()->all());
    }

    /** @test */
    public function puede_obtener_un_producto_especifico()
    {
        $product = Product::first();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                    ],
                ]);
    }

    /** @test */
    public function puede_hacer_busqueda_rapida()
    {
        $response = $this->getJson('/api/products/search?q=whey');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'price',
                            'image',
                            'url',
                        ]
                    ],
                ])
                ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function puede_verificar_stock_disponible()
    {
        $product = Product::first();

        $response = $this->postJson("/api/products/{$product->id}/check-stock", [
            'quantity' => 10,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'product_id' => $product->id,
                        'requested_quantity' => 10,
                        'is_available' => true,
                    ],
                ]);
    }

    /** @test */
    public function detecta_cuando_no_hay_stock_suficiente()
    {
        $product = Product::first();

        $response = $this->postJson("/api/products/{$product->id}/check-stock", [
            'quantity' => 1000,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'is_available' => false,
                    ],
                ]);
    }
}

