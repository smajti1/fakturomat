<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataImportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_importers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('data_imported_buyers', function (Blueprint $table) {
            $table->integer('data_importer_id')->unsigned();
            $table->foreign('data_importer_id')->references('id')->on('data_importers')->onDelete('cascade');

            $table->integer('buyer_id')->unsigned()->nullable();
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');

            $table->integer('imported_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('data_imported_products', function (Blueprint $table) {
            $table->integer('data_importer_id')->unsigned();
            $table->foreign('data_importer_id')->references('id')->on('data_importers')->onDelete('cascade');

            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->integer('imported_id')->unsigned();
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
        Schema::dropIfExists('data_imported_products');
        Schema::dropIfExists('data_imported_buyers');
        Schema::dropIfExists('data_importers');
    }
}
