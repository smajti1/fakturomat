<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
		$payment_at = $this->faker->dateTimeBetween('-1 month');
		static $i;
        return [
			'payment' => [Invoice::PAYMENT_CASH, Invoice::PAYMENT_BANK_TRANSFER][$this->faker->numberBetween(0, 1)],
			'status' => [Invoice::STATUS_NOT_PAID, Invoice::STATUS_PAID][$this->faker->numberBetween(0, 1)],
			'payment_at' => $payment_at,
			'issue_date' => $payment_at,
			'number' => (++$i ?? $i = 1) . '/' . $payment_at->format('M') . '/' . $payment_at->format('Y'),
		];
    }
}
