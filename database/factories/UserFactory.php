<?php

use App\Models\Invoice;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/** @var $factory Illuminate\Database\Eloquent\Factory */
$factory->define(App\Models\User::class, static function (Faker $faker) {
    static $password;

    return [
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Models\Company::class, static fn (Faker $faker) => [
	'name' => $faker->company,
	'city' => $faker->city,
	'zip_code' => $faker->postcode,
	'street' => $faker->streetAddress,
	'bank_name' => $faker->company,
	'bank_account' => $faker->bankAccountNumber,
]);

$factory->define(App\Models\Buyer::class, static fn (Faker $faker) => [
	'name' => $faker->company,
	'city' => $faker->city,
	'zip_code' => $faker->postcode,
	'street' => $faker->streetAddress,
]);

$factory->define(App\Models\Product::class, static fn (Faker $faker) => [
	'name' => $faker->name,
	'measure_unit' => array_rand(config('invoice.measure_units.' . config('app.locale'))),
	'price' => $faker->randomFloat(null, 0.01, 100_000),
	'tax_percent' => $faker->randomElement(config('invoice.tax_rates.' . config('invoice.currency')))['id'],
]);
$factory->define(App\Models\Invoice::class, static function (Faker $faker) {
	$payment_at = $faker->dateTimeBetween('-1 month');
	static $i;

	return [
		'payment' => [Invoice::PAYMENT_CASH, Invoice::PAYMENT_BANK_TRANSFER][$faker->numberBetween(0, 1)],
		'status' => [Invoice::STATUS_NOT_PAID, Invoice::STATUS_PAID][$faker->numberBetween(0, 1)],
		'payment_at' => $payment_at,
		'issue_date' => $payment_at,
		'number' => (++$i ?? $i = 1) . '/' . $payment_at->format('M') . '/' . $payment_at->format('Y'),
	];
});
