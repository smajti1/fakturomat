<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Buyer;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSmajtiSeeder extends Seeder
{

    public function run(): void
    {
        $user = User::factory()->create([
            'email' => 'smajti1@gmail.com',
            'password' => bcrypt('qwerty'),
        ]);

        $company = Company::factory()->create(['user_id' => $user]);
        $buyer_list = Buyer::factory(4)->create(['user_id' => $user]);
        $product_list = Product::factory(10)->create(['user_id' => $user]);

        Invoice::factory(6)
            ->create([
                'user_id' => $user,
                'company_id' => $company,
                'buyer_id' => $buyer_list->random(),
            ])
            ->each(static function (Invoice $invoice) use ($product_list, $buyer_list) {
                $invoice_total_price = 0;
                $tmp_product_list = [];
                /** @phpstan-ignore argument.type */
                foreach ($product_list->random(random_int(1, count($product_list) - 1)) as $product) {
                    $amount = random_int(0, 2_000);
                    $tax_percent = is_numeric($product->calculateVat()) ? $product->calculateVat() : 1;
                    $invoice_total_price += $product->price * $tax_percent * $amount;
                    $tmp_product_list[] = array_merge($product->only(['name', 'measure_unit', 'price', 'tax_percent']),
                        ['amount' => $amount]);
                }
                $invoice->buyer()->associate($buyer_list->random());
                $invoice->setAttribute('price', $invoice_total_price);
                $invoice->invoice_products()->createMany($tmp_product_list);
                $invoice->save();
            });
    }
}
