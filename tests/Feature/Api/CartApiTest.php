<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $category = Category::create([
            'name' => 'Proteínas',
            'slug' => 'proteinas',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Proteína Whey',
            'slug' => 'proteina-whey',
            'description' => 'Proteína de suero',
            'price' => 599.99,
            'stock' => 50,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function puede_obtener_carrito_vacio()
    {
        $response = $this->getJson('/api/cart');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'items' => [],
                        'count' => 0,
                        'total_items' => 0,
                    ],
                ]);
    }

    /** @test */
    public function puede_agregar_producto_al_carrito()
    {
        $response = $this->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Producto agregado al carrito',
                ])
                ->assertJsonPath('data.count', 1)
                ->assertJsonPath('data.total_items', 2);
    }

    /** @test */
    public function no_puede_agregar_mas_productos_que_el_stock()
    {
        $response = $this->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 1000,
        ]);

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                ]);
    }

    /** @test */
    public function puede_actualizar_cantidad_en_carrito()
    {
        // Primero agregar
        $this->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        // Luego actualizar
        $response = $this->putJson("/api/cart/{$this->product->id}", [
            'quantity' => 5,
        ]);

        $response->assertStatus(200)
                ->assertJsonPath('data.total_items', 5);
    }

    /** @test */
    public function puede_eliminar_producto_del_carrito()
    {
        // Primero agregar
        $this->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        // Luego eliminar
        $response = $this->deleteJson("/api/cart/{$this->product->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Producto eliminado del carrito',
                ])
                ->assertJsonPath('data.count', 0);
    }

    /** @test */
    public function puede_vaciar_todo_el_carrito()
    {
        // Agregar productos
        $this->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        // Vaciar carrito
        $response = $this->deleteJson('/api/cart');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Carrito vaciado',
                    'data' => [
                        'items' => [],
                        'count' => 0,
                    ],
                ]);
    }

    /** @test */
    public function calcula_correctamente_el_total()
    {
        $response = $this->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $expectedSubtotal = $this->product->price * 2;
        $expectedTax = round($expectedSubtotal * 0.16, 2);
        $expectedTotal = round($expectedSubtotal * 1.16, 2);

        $response->assertStatus(200)
                ->assertJsonPath('data.subtotal', $expectedSubtotal)
                ->assertJsonPath('data.tax', $expectedTax)
                ->assertJsonPath('data.total', $expectedTotal);
    }
}

