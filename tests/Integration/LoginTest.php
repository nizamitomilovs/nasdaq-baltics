<?php

declare(strict_types=1);

namespace Tests\Integration;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    public function testShowLoginPage(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function testLoginUserWithIncorrectPassword(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@gmail.com',
            'password' => Hash::make('pewpewpew')
        ]);

        $response = $this->from('/login')->post('login', [
            'email' => 'testing@gmail.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHasErrors();

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();

    }

    public function testLoginUser(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@gmail.com',
            'password' => Hash::make('pewpewpew')
        ]);

        $response = $this->post('login', [
            'email' => 'testing@gmail.com',
            'password' => 'pewpewpew'
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function testLogout(): void
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user)->get('/logout');
        $this->assertGuest();
    }


}
