<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
			'name' => $this->faker->name,
			'measure_unit' => array_rand(config('invoice.measure_units.' . config('app.locale'))),
			'price' => $this->faker->randomFloat(null, 0.01, 100_000),
			'tax_percent' => $this->faker->randomElement(config('invoice.tax_rates.' . config('invoice.currency')))['id'],
		];
    }
}
