<?php

declare(strict_types=1);

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', static function (Blueprint $table) {
            $table->float('price_net')->unsigned()->nullable()->after('price')->default(0.0);
        });
        foreach (Invoice::all() as $invoice) {
            $invoice->price_net = $invoice->invoice_products->sum(static fn(InvoiceProduct $product) => $product->netPrice());
            $invoice->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', static function (Blueprint $table) {
            $table->dropColumn('price_net');
        });
    }
};
