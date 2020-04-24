<?php

use App\Models\Buyer;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSmajtiSeeder extends Seeder
{

	public function run()
	{
		$user = factory(User::class)->create([
			'email' => 'smajti1@gmail.com',
			'password' => bcrypt('qwerty'),
		 ]);

		$company = factory(Company::class)
			->create()
			->each(fn (Company $company) =>	$company->user()->associate($user)->save());

		$buyer_list = factory(Buyer::class, 4)
			->create()
			->each(fn(Buyer $buyer) => $buyer->user()->associate($user)->save());

		$product_number = 10;
		$product_list = factory(Product::class, $product_number)
			->create()
			->each(fn(Product $product) => $product->user()->associate($user)->save());

		factory(Invoice::class, 6)
			->create()
			->each(static function(Invoice $invoice) use ($user, $company, $buyer_list, $product_list, $product_number) {
				$invoice->user()->associate($user)->save();
				$invoice->company()->associate($company)->save();
				$invoice->buyer()->associate($buyer_list->random())->save();

				$invoice_total_price = 0;
				$tmp_product_list = [];
				foreach ($product_list->random(random_int(0, $product_number - 1)) as $product) {
					$amount = random_int(0, 2_000);
					$tax_percent = is_numeric($product->calculateVat()) ? $product->calculateVat() : 1;
					$invoice_total_price += $product->price * $tax_percent * $amount;
					$tmp_product_list[] = array_merge($product->only(['name', 'measure_unit', 'price', 'tax_percent']),
													  ['amount' => $amount]);
				}
				$invoice->setAttribute('price', $invoice_total_price);
				$invoice->invoice_products()->createMany($tmp_product_list);
				$invoice->save();
			});
	}
}
