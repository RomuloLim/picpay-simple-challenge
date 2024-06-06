<?php

namespace Tests\Feature\User;

use App\Enums\UserType;
use App\Http\Requests\User\CreateRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\{Event, Validator};
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function testUserCreationWithValidData()
    {
        $user = User::factory()->unverified()->make();

        Event::fake();

        Event::assertNotDispatched(
            Registered::class
        );

        $response = $this->postJson(route('user.store'), ['name' => $user->name,
            'email'                                              => $user->email,
            'identifier'                                         => $user->identifier,
            'type'                                               => $user->type,
            'password'                                           => 'password',
            'password_confirmation'                              => 'password',
        ]);

        $response->assertCreated();

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
            'name'       => $user->name,
            'email'      => $user->email,
            'identifier' => $user->identifier,
            'type'       => $user->type,
        ]);

        $user = User::whereEmail($user->email)->first();

        $this->assertTrue(password_verify('password', $user->password));
        $this->assertTrue(! $user->hasVerifiedEmail());

        Event::assertDispatched(Registered::class);
    }

    #[DataProvider('invalidUsers')]
    public function testFailCreationWithInvalidData(array $invalidData)
    {
        $request = new CreateRequest();

        $response = $this->postJson(route('user.store'), $invalidData);

        $response->assertUnprocessable();

        $validator = Validator::make($invalidData, $request->rules());

        $this->assertDatabaseCount(User::class, 0);
        $this->assertFalse($validator->passes());
    }

    public function testFailCreationWithNotUniqueIdentifier()
    {
        $user = User::factory()->create();

        $duplicateUser = User::factory()->make(['identifier' => $user->identifier]);

        $response = $this->postJson(route('user.store'), [
            'name'                  => $duplicateUser->name,
            'email'                 => $duplicateUser->email,
            'identifier'            => $duplicateUser->identifier,
            'type'                  => $duplicateUser->type,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('identifier');

        $response->assertJsonFragment([
            'message' => __('validation.unique', ['attribute' => 'identifier']),
        ]);

        $this->assertDatabaseCount(User::class, 1);
    }

    public function testFailCreationWithNotUniqueEmail()
    {
        $user = User::factory()->create();

        $duplicateUser = User::factory()->make(['email' => $user->email]);

        $response = $this->postJson(route('user.store'), [
            'name'                  => $duplicateUser->name,
            'email'                 => $duplicateUser->email,
            'identifier'            => $duplicateUser->identifier,
            'type'                  => $duplicateUser->type,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('email');

        $response->assertJsonFragment([
            'message' => __('validation.unique', ['attribute' => 'email']),
        ]);

        $this->assertDatabaseCount(User::class, 1);
    }

    public static function invalidUsers()
    {
        $validUser = [
            'name'                  => 'Test',
            'email'                 => 'test@mail.com',
            'identifier'            => '12345678909',
            'type'                  => UserType::Common,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];

        yield 'empty data' => [[]];
        yield 'empty name' => [array_merge($validUser, ['name' => ''])];
        yield 'empty email' => [array_merge($validUser, ['email' => ''])];
        yield 'empty identifier' => [array_merge($validUser, ['identifier' => ''])];
        yield 'empty type' => [array_merge($validUser, ['type' => ''])];
        yield 'empty password' => [array_merge($validUser, ['password' => ''])];
        yield 'empty password confirmation' => [array_merge($validUser, ['password_confirmation' => ''])];
        yield 'invalid email' => [array_merge($validUser, ['email' => 'invalid'])];
        yield 'invalid identifier' => [array_merge($validUser, ['identifier' => 'invalid'])];
        yield 'invalid type' => [array_merge($validUser, ['type' => 'invalid'])];
        yield 'password mismatch' => [array_merge($validUser, ['password_confirmation' => 'mismatch'])];
    }
}
