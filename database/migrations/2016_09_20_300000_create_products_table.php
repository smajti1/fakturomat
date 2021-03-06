<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('measure_unit');
            $table->float('price')->unsigned();
            $table->string('tax_percent')->default('');
            $table->string('slug');

            $table->integer('user_id')->unsigned()->nullable(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('invoice_products', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('measure_unit');
            $table->float('price')->unsigned();
            $table->string('tax_percent')->default('');
            $table->float('amount')->unsigned();

            $table->integer('invoice_id')->unsigned()->nullable(false);
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_products');
        Schema::dropIfExists('products');
    }
}
