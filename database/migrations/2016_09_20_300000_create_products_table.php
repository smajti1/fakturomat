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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('measure_unit');
            $table->mediumInteger('price')->unsigned();
            $table->tinyInteger('vat')->unsigned();
            $table->string('slug');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('invoice_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('measure_unit');
            $table->mediumInteger('price')->unsigned();
            $table->tinyInteger('vat')->unsigned();
            $table->tinyInteger('amount')->unsigned();

            $table->integer('invoice_id')->unsigned()->nullable();
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
