<?php

declare(strict_types=1);


use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ksef_tokens', static function (Blueprint $table) {
            $table->id();
            $table->string('ksef_token', 511)->isNotEmpty();
            $table->foreignIdFor(Company::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ksef_tokens');
    }
};
