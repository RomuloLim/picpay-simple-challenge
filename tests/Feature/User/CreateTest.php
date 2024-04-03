<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_user_creation_with_valid_data()
    {
        $user = User::factory()->make();

        $response = $this->postJson(route('user.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'identifier' => $user->identifier,
            'type' => $user->type,
            'password' => 'password',
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'identifier',
                'type',
            ],
        ]);

        $this->assertDatabaseHas(User::class, [
            'name' => $user->name,
            'email' => $user->email,
            'identifier' => $user->identifier,
            'type' => $user->type,
        ]);

        $this->assertTrue(password_verify('password', User::whereEmail($user->email)->first()->password));
    }
}
