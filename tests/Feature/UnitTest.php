<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UnitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);
    }

    public function test_guest_can_view_catalog(): void
    {
        $response = $this->get('/catalog');
        $response->assertStatus(200);
    }

    public function test_customer_can_view_unit_detail(): void
    {
        $unit = Unit::factory()->create();
        $response = $this->actingAs($this->customer)->get('/units/' . $unit->id);
        $response->assertStatus(200);
        $response->assertViewIs('units.show');
        $response->assertSee($unit->name);
    }

    public function test_admin_can_create_unit(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/units', [
            'name' => 'New Test Unit',
            'category_id' => $category->id,
            'description' => 'A description for the new unit.',
            'address' => '123 Test Street',
            'price_per_day' => 100000,
            'price_per_week' => 600000,
            'price_per_month' => 2500000,
            'deposit_amount' => 50000,
            'max_guests' => 4,
            'bedrooms' => 2,
            'beds' => 2,
            'bathrooms' => 1,
            'area' => 50,
            'asset_number' => 'AST-001',
            'amenities' => ['WiFi', 'AC'],
            'photos' => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.jpg'),
            ],
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/units');
        $this->assertDatabaseHas('units', ['name' => 'New Test Unit']);
        $unit = \App\Models\Unit::where('name', 'New Test Unit')->first();
        $this->assertNotNull($unit);
        $this->assertNotEmpty($unit->photos);
        Storage::disk('public')->assertExists($unit->photos[0]);
    }

    public function test_admin_can_update_unit(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        $unit = Unit::factory()->create(['name' => 'Original Unit', 'category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->put('/admin/units/' . $unit->id, [
            'name' => 'Updated Unit Name',
            'category_id' => $category->id,
            'description' => 'Updated description.',
            'address' => 'Updated Address',
            'price_per_day' => 150000,
            'price_per_week' => 900000,
            'price_per_month' => 3500000,
            'deposit_amount' => 75000,
            'max_guests' => 6,
            'bedrooms' => 3,
            'beds' => 3,
            'bathrooms' => 2,
            'area' => 70,
            'asset_number' => 'AST-002',
            'status' => 'ready',
            'amenities' => ['WiFi', 'AC', 'Pool'],
            'is_active' => false,
        ]);

        $response->assertRedirect('/admin/units');
        $this->assertDatabaseHas('units', ['name' => 'Updated Unit Name', 'is_active' => false]);
        $this->assertDatabaseMissing('units', ['name' => 'Original Unit']);
    }

    public function test_admin_can_delete_unit(): void
    {
        $unit = Unit::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/admin/units/' . $unit->id);

        $response->assertRedirect('/admin/units');
        $this->assertDatabaseMissing('units', ['id' => $unit->id]);
    }

    public function test_catalog_can_be_sorted_by_price_asc(): void
    {
        Unit::factory()->create(['price_per_day' => 200000, 'is_active' => true, 'status' => 'ready']);
        Unit::factory()->create(['price_per_day' => 100000, 'is_active' => true, 'status' => 'ready']);

        $response = $this->get('/catalog?sort=termurah');
        $response->assertStatus(200);
        $response->assertViewHas('units');
    }

    public function test_catalog_can_be_sorted_by_price_desc(): void
    {
        Unit::factory()->create(['price_per_day' => 100000, 'is_active' => true, 'status' => 'ready']);
        Unit::factory()->create(['price_per_day' => 200000, 'is_active' => true, 'status' => 'ready']);

        $response = $this->get('/catalog?sort=termahal');
        $response->assertStatus(200);
        $response->assertViewHas('units');
    }

    public function test_catalog_can_be_sorted_by_newest(): void
    {
        $response = $this->get('/catalog?sort=terbaru');
        $response->assertStatus(200);
        $response->assertViewHas('units');
    }

    public function test_catalog_can_be_sorted_by_popular(): void
    {
        $response = $this->get('/catalog?sort=terpopuler');
        $response->assertStatus(200);
        $response->assertViewHas('units');
    }

    public function test_similar_units_shown_on_detail_page(): void
    {
        $category = Category::factory()->create();
        $unit = Unit::factory()->create(['category_id' => $category->id]);
        Unit::factory(3)->create(['category_id' => $category->id, 'is_active' => true, 'status' => 'ready']);

        $response = $this->get('/units/' . $unit->id);
        $response->assertStatus(200);
        $response->assertViewHas('similarUnits');
    }

    public function test_sitemap_xml(): void
    {
        Unit::factory()->create(['is_active' => true]);
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }
}
