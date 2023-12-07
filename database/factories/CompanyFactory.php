<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
			'name' => $this->faker->company,
			'city' => $this->faker->city,
			'zip_code' => $this->faker->postcode,
			'street' => $this->faker->streetAddress,
			'bank_name' => $this->faker->company,
			'bank_account' => $this->faker->iban(),
		];
    }
}
