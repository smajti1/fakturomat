<?php

declare(strict_types=1);

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
            $table->string('ksef_invoice_reference_number', 255)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', static function (Blueprint $table) {
            $table->dropColumn('ksef_invoice_reference_number');
        });
    }
};
