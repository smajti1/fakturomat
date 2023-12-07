<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Buyer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buyer>
 */
class BuyerFactory extends Factory
{
    protected $model = Buyer::class;

    public function definition(): array
    {
        return [
			'name' => $this->faker->company,
			'city' => $this->faker->city,
			'zip_code' => $this->faker->postcode,
			'street' => $this->faker->streetAddress,
		];
    }
}
