<?php

namespace Tests\Feature;

use App\Models\User;
use App\Steps\Account\SetUserEmailAndPassword;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AccountWizardControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateAccountWrongPassword(): void
    {
        $this->get($this->getBaseUrl() . '/register');
        $faker = Factory::create();
        $password = $faker->password(8);
        $response = $this->post(
        	$this->getBaseUrl() . '/konto/' . SetUserEmailAndPassword::$slug,
			[
				'email' => $faker->email,
				'password' => $password,
				'password_confirmation' => $password . 'asd',
			]
		);

        $response->assertRedirect($this->getBaseUrl() . '/register')->assertSessionHasErrors(['password']);
    }

    public function testCreateAccountEmailAlreadyInUse(): void
    {
        $this->get($this->getBaseUrl() . '/register');
        $faker = Factory::create();
        $password = $faker->password(8);
        $email = $faker->email;
        User::create([
            'email'    => $email,
            'password' => $password,
        ]);
        $response = $this->post(
        	$this->getBaseUrl() . '/konto/' . SetUserEmailAndPassword::$slug,
			[
				'email' => $email,
				'password' => $password,
				'password_confirmation' => $password,
        	]
		);

        $response->assertRedirect($this->getBaseUrl() . '/register')->assertSessionHasErrors(['email']);
    }
}
