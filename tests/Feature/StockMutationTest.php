<?php

namespace Tests\Feature;

use App\Enums\PurchaseStatus;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\StockMutation;
use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StockMutationTest extends TestCase
{
    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $this->seed(RolePermissionSeeder::class);
        $this->owner = User::factory()->create(['level' => 'owner']);
        $this->owner->assignRole('owner');
    }

    public function test_adjust_stock_updates_product_and_writes_an_audit_record(): void
    {
        $product = Product::factory()->create([
            'user_id' => $this->owner->id,
            'quantity' => 10,
        ]);

        $product->adjustStock(-3, 'manual_adjustment', userId: $this->owner->id);

        $this->assertSame(7, $product->fresh()->quantity);
        $this->assertDatabaseHas('stock_mutations', [
            'product_id' => $product->id,
            'quantity_before' => 10,
            'quantity_change' => -3,
            'quantity_after' => 7,
            'mutation_type' => 'manual_adjustment',
            'created_by' => $this->owner->id,
        ]);
    }

    public function test_stock_cannot_be_reduced_below_zero(): void
    {
        $product = Product::factory()->create([
            'user_id' => $this->owner->id,
            'quantity' => 2,
        ]);

        try {
            $product->adjustStock(-3, 'order_completed', userId: $this->owner->id);
            $this->fail('Expected insufficient stock validation error.');
        } catch (ValidationException) {
            $this->assertSame(2, $product->fresh()->quantity);
            $this->assertDatabaseCount('stock_mutations', 0);
        }
    }

    public function test_purchase_can_only_add_stock_once(): void
    {
        $product = Product::factory()->create([
            'user_id' => $this->owner->id,
            'quantity' => 10,
        ]);
        $supplier = Supplier::factory()->create(['user_id' => $this->owner->id]);
        $purchase = Purchase::create([
            'supplier_id' => $supplier->id,
            'date' => now()->format('Y-m-d'),
            'purchase_no' => 'PRS-TEST-1',
            'status' => PurchaseStatus::PENDING,
            'total_amount' => 5000,
            'created_by' => $this->owner->id,
            'user_id' => $this->owner->id,
            'uuid' => Str::uuid(),
        ]);
        PurchaseDetails::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'item_type' => 'product',
            'quantity' => 5,
            'unitcost' => 1000,
            'total' => 5000,
        ]);

        $this->actingAs($this->owner)
            ->post(route('purchases.update', $purchase->uuid))
            ->assertRedirect();
        $this->actingAs($this->owner)
            ->post(route('purchases.update', $purchase->uuid))
            ->assertRedirect();

        $this->assertSame(15, $product->fresh()->quantity);
        $this->assertSame(PurchaseStatus::APPROVED, $purchase->fresh()->status);
        $this->assertSame(1, StockMutation::where([
            'product_id' => $product->id,
            'mutation_type' => 'purchase_received',
        ])->count());
    }
}
