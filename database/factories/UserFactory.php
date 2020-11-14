<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
		static $password;

		return [
			'email' => $this->faker->unique()->safeEmail,
			'password' => $password ?: $password = bcrypt('secret'),
			'remember_token' => Str::random(10),
		];
    }
}
