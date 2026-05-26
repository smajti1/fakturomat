<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use Faker\Provider\pl_PL\Payment;
use Faker\Provider\pl_PL\Person;
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
            'tax_id_number' => Person::taxpayerIdentificationNumber(),
            'bank_name' => Payment::bank(),
            'bank_account' => Payment::bankAccountNumber(),
        ];
    }
}
