<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user for testing
        $this->admin = User::factory()->create(['role' => 'admin']);
        // Create a customer user for testing
        $this->customer = User::factory()->create(['role' => 'customer']);
    }

    public function test_admin_can_view_categories_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Test Category',
            'description' => 'A description for the test category',
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create(['name' => 'Old Category']);

        $response = $this->actingAs($this->admin)->put('/admin/categories/' . $category->id, [
            'name' => 'Updated Category',
            'description' => 'Updated description',
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
        $this->assertDatabaseMissing('categories', ['name' => 'Old Category']);
    }

    public function test_admin_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/categories/' . $category->id);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_customer_cannot_access_admin_categories(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/categories');
        $response->assertStatus(403); // Forbidden
    }
}
