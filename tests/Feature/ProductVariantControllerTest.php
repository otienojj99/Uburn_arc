<?php

namespace Tests\Feature;

use App\Models\ProductItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_soft_deletes_a_variant_via_the_api(): void
    {
        $user = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $product = ProductItem::create([
            'sku' => 'SKU-001',
            'name' => 'Test Product',
            'slug' => 'test-product',
            'selling_price' => 100.00,
        ]);

        $variant = ProductVariant::create([
            'product_item_id' => $product->id,
            'sku' => 'VAR-001',
            'name' => 'Test Variant',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/products/{$product->id}/variants/{$variant->id}");

        $response->assertOk();
        $this->assertSoftDeleted('product_variants', ['id' => $variant->id]);
    }
}
