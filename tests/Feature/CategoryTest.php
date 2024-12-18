<?php

namespace Tests\Feature;

use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use RefreshDatabase;

    public function testExample()
    {
        $response = $this->get('/categories');

        $response->assertStatus(200);
    }

    public function testSaveCategory()
    {
        $category = new Category();
        $category->name = "test category";
        $category->save();
        $this->assertDatabaseHas('categories', [
            'name' => 'test category'
        ]);
    }

    public function testCategoryStoreValid()
    {
        $payload = [
            'name' => 'new category',
        ];
        $response = $this->postJson('/api/categories', $payload);
        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Category created successfully',
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => $payload['name'],
        ]);
    }

    public function testCategoryDestroyValid()
    {
        $category = new Category();
        $category->name = "test category";
        $category->save();

        $response = $this->deleteJson("/api/categories/{$category->id}");
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Category deleted successfully',
            ]);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function testCategoryIndexValid()
    {
        $category1 = new Category();
        $category1->name = "Category 1";
        $category1->save();
        $category2 = new Category();
        $category2->name = "Category 2";
        $category2->save();

        $response = $this->getJson('/api/categories');
        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                ['id' => $category1->id, 'name' => $category1->name],
                ['id' => $category2->id, 'name' => $category2->name],
            ]);
    }


    public function testCategoryUpdateValid()
    {
        $category = new Category();
        $category->name = "test Category";
        $category->save();

        $payload = [
            'name' => 'Updated test Category',
        ];
        $response = $this->patchJson("/api/categories/{$category->id}", $payload);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Category updated successfully',
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $payload['name'],
        ]);
    }
}
