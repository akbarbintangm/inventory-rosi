<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'date' => now()->format('Y-m-d'),
            'purchase_no' => fake()->unique()->bothify('PRS-######'),
            'status' => fake()->randomElement([0, 1]),
            'total_amount' => fake()->randomNumber(2),
            'created_by' => User::factory(),
            'user_id' => User::factory(),
            'uuid' => Str::uuid(),
        ];
    }
}
