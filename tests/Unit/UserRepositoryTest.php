<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Exceptions\EntityAlreadyExistsException;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;
use Database\Factories\UserFactory;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateUser(): void
    {
        $userData = [
            'name' => 'test',
            'email' => 'testing@gmail.com',
            'password' => 'pewpewpew'
        ];

        $mock = Mockery::mock(
            UserRepositoryInterface::class,
            function (MockInterface $mock) use ($userData) {
                $mock->shouldReceive('create')
                    ->with($userData)
                    ->andReturn(User::class);
            }
        );

        $repository = new UserRepository();
        $user = $repository->create($userData);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['email'], $user->email);
    }

    public function testCreateExistingUser(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@gmail.com',
            'password' => Hash::make('pewpewpew')
        ]);

        $userData = [
            'name' => 'test',
            'email' => 'testing@gmail.com',
            'password' => 'pewpewpew'
        ];

        $mock = Mockery::mock(
            UserRepositoryInterface::class,
            function (MockInterface $mock) use ($userData) {
                $mock->shouldReceive('create')
                    ->with($userData)
                    ->andReturn(User::class);
            }
        );

        $this->expectException(EntityAlreadyExistsException::class);
        $this->expectExceptionMessage('Entity: User already exists in database.');

        $repository = new UserRepository();
        $repository->create($userData);
    }
}
