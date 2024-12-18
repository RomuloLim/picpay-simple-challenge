<?php

namespace Database\Factories;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userType = fake()->randomElement([UserType::Common, UserType::Logistic]);

        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'identifier'        => $userType === UserType::Common ? fake('pt_BR')->unique()->cpf(false) : fake('pt_BR')->unique()->cnpj(false),
            'type'              => $userType,
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            'balance'           => rand(0, 10000),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model's type is common.
     */
    public function common(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'       => UserType::Common,
            'identifier' => fake()->unique()->cpf(false),
        ]);
    }

    /**
     * Indicate that the model's type is common.
     */
    public function logistic(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'       => UserType::Logistic,
            'identifier' => fake()->unique()->cnpj(false),
        ]);
    }
}
