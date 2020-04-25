<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->unique();
            $table->string('city')->default('');
            $table->string('zip_code')->default('');
            $table->string('street')->default('');
            $table->string('tax_id_number')->default('');
            $table->string('regon')->default('');
            $table->string('email')->default('');
            $table->string('website')->default('');
            $table->string('phone')->default('');
            $table->string('bank_name')->default('');
            $table->string('bank_account')->default('');
            $table->string('slug')->default('');

            $table->integer('user_id')->unsigned()->nullable(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
