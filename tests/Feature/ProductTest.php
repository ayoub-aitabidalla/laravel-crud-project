<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{

    use RefreshDatabase;
    public function testExample()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
    }

    public function testProductIndexValid()
    {
        $category = new Category();
        $category->name = 'Test Category';
        $category->save();

        $product1 = new Product();
        $product1->name = 'Product 1';
        $product1->description = 'Description for Product 1';
        $product1->price = 100;
        $product1->category_id = $category->id;
        $product1->save();

        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Products retrieved successfully!',
            ])
            ->assertJsonCount(1, 'products')
            ->assertJson([
                'products' => [
                    [
                        'id' => $product1->id,
                        'name' => $product1->name,
                        'description' => $product1->description,
                        'price' => $product1->price,
                        'category_id' => $category->id,
                        'category' => [
                            'id' => $category->id,
                            'name' => $category->name,
                        ],
                    ],
                ],
            ]);
    }

    public function testProductUpdateValid()
    {
        $category = new Category();
        $category->name = 'Test Category';
        $category->save();

        $product = new Product();
        $product->name = 'test Product';
        $product->description = 'description';
        $product->price = 100;
        $product->category_id = $category->id;
        $product->save();

        $payload = [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 150,
            'category_id' => $category->id,
        ];

        $response = $this->patchJson("/api/products/{$product->id}", $payload);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product updated successfully',
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 150,
            'category_id' => $category->id,
        ]);
    }

    public function testProductDestroyValid()
    {
        $category = new Category();
        $category->name = 'Test Category';
        $category->save();

        $product = new Product();
        $product->name = 'test product';
        $product->description = 'Description of the product';
        $product->price = 100;
        $product->category_id = $category->id;
        $product->save();

        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'product deleted successfully',
            ]);
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function testProductStoreValid()
    {
        $category = new Category();
        $category->name = 'Test Category';
        $category->save();

        $payload = [
            'name' => 'New Product',
            'description' => 'Product description here',
            'price' => 100,
            'category_id' => $category->id,
        ];
        $response = $this->postJson('/api/products', ($payload));
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'product created successfully',
            ]);
        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'description' => 'Product description here',
            'price' => 100,
            'category_id' => $category->id,
        ]);
    }
}
