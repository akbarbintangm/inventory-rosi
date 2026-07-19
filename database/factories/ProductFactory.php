<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'uuid' => Str::uuid(),
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'code' => fake()->unique()->bothify('PC-####'),
            'category_id' => Category::factory(),
            'unit_id' => Unit::factory(),
            'quantity' => fake()->randomNumber(2),
            'buying_price' => fake()->randomNumber(2),
            'selling_price' => fake()->randomNumber(2),
            'quantity_alert' => fake()->randomElement([5,10,15]),
            'tax' => fake()->randomElement([5,10,15,20,25]),
            'tax_type' => fake()->randomElement([0, 1]),
        ];
    }
}
