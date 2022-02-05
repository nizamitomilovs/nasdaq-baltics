<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;
use App\Repositories\Exceptions\EntityAlreadyExistsException;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $userData): User
    {
        $user = User::where('email', $userData['email'])->first();

        if (null !== $user) {
            throw EntityAlreadyExistsException::isInDatabases('User');
        }

        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);
    }
}
