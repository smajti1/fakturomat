<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Steps\Account\SetAddress;
use App\Steps\Account\SetCompanyBasicData;
use App\Steps\Account\SetUserEmailAndPassword;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AccountWizardControllerTest extends TestCase
{
    use DatabaseMigrations;

    private Generator $faker;

    public function testCreateAccountWrongPassword(): void
    {
        $this->get($this->getBaseUrl() . '/register');
        $password = $this->faker->password(8);
        $response = $this->post(
            $this->getBaseUrl() . '/konto/' . SetUserEmailAndPassword::$slug,
            [
                'email' => $this->faker->email,
                'password' => $password,
                'password_confirmation' => $password . 'asd',
            ],
        );

        $response->assertRedirect($this->getBaseUrl() . '/register')->assertSessionHasErrors(['password']);
    }

    public function testCreateAccountEmailAlreadyInUse(): void
    {
        $this->get($this->getBaseUrl() . '/register');
        $password = $this->faker->password(8);
        $email = $this->faker->email;
        $user = new User();
        $user->fill([
            'email' => $email,
            'password' => $password,
        ]);
        $user->save();
        $response = $this->post(
            $this->getBaseUrl() . '/konto/' . SetUserEmailAndPassword::$slug,
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
            ],
        );

        $response->assertRedirect($this->getBaseUrl() . '/register')->assertSessionHasErrors(['email']);
    }

    public function testSetCompanyBasicData(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post(
                $this->getBaseUrl() . '/konto/' . SetCompanyBasicData::$slug,
                [
                    'company_name' => $this->faker->company,
                    'tax_id_number' => '2672349767',
                    'regon' => 658190710,
                    'bank_account' => $this->faker->numberBetween(),
                ]);
        $response->assertRedirect($this->getBaseUrl() . '/konto/' . SetAddress::$slug);
    }

    protected function setUp(): void
    {
        $this->faker = Factory::create();
        parent::setUp();
    }
}
