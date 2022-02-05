<?php

declare(strict_types=1);

namespace Tests\Integration;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function testNotValidEmail(): void
    {
        $response = $this->post('/register', [
            'email' => 'wrong_email',
            'name' => 'hoho',
            'password' => 'secretpassword'
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors()
            ->assertRedirect('/login');
    }

    public function testRegisterWhenEmailIsUser(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@gmail.com',
            'password' => Hash::make('pewpewpew')
        ]);

        $response = $this->post('/register', [
            'email' => 'testing@gmail.com',
            'name' => 'hoho',
            'password' => 'secretpassword'
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors()
            ->assertRedirect('/login');

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testRegisterUser(): void
    {
        $response = $this->post('/register', [
            'email' => 'test@gmail.com',
            'name' => 'hoho',
            'password' => 'secretpassword'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com',
            'name' => 'hoho',
        ]);
    }
}
