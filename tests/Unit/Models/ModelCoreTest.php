<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\PreOrder;
use App\Models\PreOrderDetail;
use App\Models\Product;
use App\Models\Promo;
use App\Models\PromoDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ModelCoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_address_relationships_are_defined(): void
    {
        $address = new Address();

        $this->assertInstanceOf(BelongsTo::class, $address->user());
        $this->assertInstanceOf(HasMany::class, $address->preOrders());
    }

    public function test_preorder_casts_dates_and_datetime_fields(): void
    {
        $preOrder = PreOrder::factory()->create([
            'actual_periode' => '2026-04-27',
            'start_periode' => '2026-05-01',
            'end_periode' => '2026-05-03',
            'paid_at' => '2026-04-27 12:13:14',
        ]);

        $preOrder->refresh();

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $preOrder->actual_periode);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $preOrder->start_periode);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $preOrder->end_periode);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $preOrder->paid_at);
    }

    public function test_preorder_relationships_are_defined(): void
    {
        $preOrder = new PreOrder();

        $this->assertInstanceOf(HasMany::class, $preOrder->detailPreOrders());
        $this->assertInstanceOf(BelongsTo::class, $preOrder->customer());
        $this->assertInstanceOf(BelongsTo::class, $preOrder->address());
    }

    public function test_preorder_detail_relationships_are_defined(): void
    {
        $detail = new PreOrderDetail();

        $this->assertInstanceOf(BelongsTo::class, $detail->preOrder());
        $this->assertInstanceOf(BelongsTo::class, $detail->product());
        $this->assertInstanceOf(BelongsTo::class, $detail->promo());
    }

    public function test_product_image_accessor_returns_null_without_media(): void
    {
        $product = Product::factory()->create();

        $this->assertNull($product->image);
    }

    public function test_promo_percentage_accessor_returns_rounded_discount(): void
    {
        $promo = Promo::factory()->create([
            'actual_price' => 100000,
            'price' => 75000,
        ]);

        $this->assertEquals(25, $promo->percentage);
    }

    public function test_promo_percentage_returns_zero_if_actual_price_is_zero(): void
    {
        $promo = Promo::factory()->create([
            'actual_price' => 0,
            'price' => 1,
        ]);

        $this->assertSame(0, $promo->percentage);
    }

    public function test_promo_casts_are_applied(): void
    {
        $promo = Promo::factory()->create([
            'price' => '15000',
            'actual_price' => '20000',
            'date_until' => '2026-05-30',
        ]);

        $promo->refresh();

        $this->assertIsInt($promo->price);
        $this->assertIsInt($promo->actual_price);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $promo->date_until);
    }

    public function test_promo_detail_relationships_are_defined(): void
    {
        $detail = new PromoDetail();

        $this->assertInstanceOf(BelongsTo::class, $detail->promo());
        $this->assertInstanceOf(BelongsTo::class, $detail->product());
    }

    public function test_user_password_is_hashed_via_cast(): void
    {
        $user = User::factory()->create([
            'password' => 'secret-123',
        ]);

        $this->assertNotSame('secret-123', $user->password);
        $this->assertTrue(Hash::check('secret-123', $user->password));
    }

    public function test_user_has_many_addresses(): void
    {
        $user = User::factory()->create();
        Address::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->address);
        $this->assertInstanceOf(HasMany::class, $user->address());
    }
}
