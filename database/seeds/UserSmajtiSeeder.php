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

		$company = factory(Company::class)->create(['user_id' => $user]);
		$buyer_list = factory(Buyer::class, 4)->create(['user_id' => $user]);
		$product_list = factory(Product::class, 10)->create(['user_id' => $user]);

		factory(Invoice::class, 6)
			->create([
				'user_id' => $user,
				'company_id' => $company,
				'buyer_id' => $buyer_list->random(),
			 ])
			->each(static function(Invoice $invoice) use ($product_list) {
				$invoice_total_price = 0;
				$tmp_product_list = [];
				foreach ($product_list->random(random_int(1, count($product_list) - 1)) as $product) {
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
