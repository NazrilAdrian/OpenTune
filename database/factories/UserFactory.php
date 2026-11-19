<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'username'        => fake()->unique()->userName(),
            'email'           => fake()->unique()->safeEmail(),
            'password'        => static::$password ??= Hash::make('password'),
            'profile_picture' => null,
            'role'            => 'user',
        ];
    }

        public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin']);
    }
}
