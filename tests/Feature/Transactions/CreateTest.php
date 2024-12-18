<?php

namespace Tests\Feature\Transactions;

use App\Enums\ErrorCodes;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private function externalServiceMock($successfully = true, $statusCode = 200)
    {
        $externalServiceUrl = config('external_services.authorization_url');

        Http::fake([
            $externalServiceUrl => Http::response([
                'status' => $successfully ? 'success' : 'fail',
                'data'   => ['authorization' => $successfully],
            ], $statusCode),
        ]);
    }

    public function test_can_transfer_to_another_user()
    {
        $this->externalServiceMock();
        $balance = rand(1, 10000);

        $user = User::factory()->common()->count(2)->create([
            'balance' => $balance,
        ]);

        $response = $this->postJson(route('transaction.store'), [
            'sender_id'   => $user[0]->id,
            'receiver_id' => $user[1]->id,
            'amount'      => rand(1, $balance),
            'description' => 'Transfer to another user',
        ]);

        $response
            ->assertAccepted()
            ->assertJsonStructure([
                'sender_id',
                'receiver_id',
                'amount',
                'description',
                'is_successful',
                'failure_reason',
                'completed_at',
            ])
            ->assertJson([
                'sender_id'     => $user[0]->id,
                'receiver_id'   => $user[1]->id,
                'is_successful' => true,
            ]);
    }

    public function test_cannot_transfer_if_user_is_a_logistic()
    {
        $this->externalServiceMock();
        $balance = rand(1, 10000);

        $commonUser   = User::factory()->common()->create();
        $logisticUser = User::factory()->logistic()->create([
            'balance' => $balance,
        ]);

        $response = $this->postJson(route('transaction.store'), [
            'sender_id'   => $logisticUser->id,
            'receiver_id' => $commonUser->id,
            'amount'      => rand(1, $balance),
            'description' => 'Logistic transferring to another user',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonStructure([
                'message',
                'errors',
            ])
            ->assertJsonValidationErrors([
                'sender_id',
            ])
            ->assertJson([
                'message' => 'Logistic users cannot transfer money',
            ]);

        $this->assertDatabaseEmpty('transactions');
    }

    public function test_cannot_transfer_if_user_has_no_sufficient_balance()
    {
        $this->externalServiceMock();
        $balance = rand(1, 10000);

        $user = User::factory()->common()->count(2)->create([
            'balance' => $balance,
        ]);

        $responseData = [
            'sender_id'   => $user[0]->id,
            'receiver_id' => $user[1]->id,
            'amount'      => $balance + 1,
            'description' => 'Transfer to another user',
        ];

        $response = $this->postJson(route('transaction.store'), $responseData);

        $response
            ->assertBadRequest()
            ->assertJsonFragment([
                'is_successful'  => false,
                'failure_reason' => ErrorCodes::INSUFFICIENT_FUNDS->getMessage(),
                'sender_id'      => $user[0]->id,
                'receiver_id'    => $user[1]->id,
            ])
            ->assertJsonStructure([
                'sender_id',
                'receiver_id',
                'amount',
                'description',
                'is_successful',
                'failure_reason',
                'completed_at',
            ]);

        $this->assertDatabaseHas('transactions', [
            ...$responseData,
            'is_successful'  => false,
            'failure_reason' => ErrorCodes::INSUFFICIENT_FUNDS->getMessage(),
            'completed_at'   => $response->json('completed_at'),
        ]);

        $this->assertDatabaseCount('transactions', 1);

        $this->assertDatabaseHas('users', [
            'id'      => $user[0]->id,
            'balance' => $balance,
        ]);

        $this->assertDatabaseHas('users', [
            'id'      => $user[1]->id,
            'balance' => $balance,
        ]);
    }

    public function test_fail_transfer_if_external_authorizer_is_down()
    {
        $this->externalServiceMock(false, 500);

        $balance = rand(1, 10000);

        $user = User::factory()->common()->count(2)->create([
            'balance' => $balance,
        ]);

        $responseData = [
            'sender_id'   => $user[0]->id,
            'receiver_id' => $user[1]->id,
            'amount'      => $balance,
            'description' => 'Transfer to another user',
        ];

        $response = $this->postJson(route('transaction.store'), $responseData);

        $response
            ->assertBadRequest()
            ->assertJsonFragment([
                'is_successful'  => false,
                'failure_reason' => ErrorCodes::EXTERNAL_SERVICE_UNAVAILABLE->getMessage(),
                'sender_id'      => $user[0]->id,
                'receiver_id'    => $user[1]->id,
            ])
            ->assertJsonStructure([
                'sender_id',
                'receiver_id',
                'amount',
                'description',
                'is_successful',
                'failure_reason',
                'completed_at',
            ]);

        $this->assertDatabaseHas('transactions', [
            ...$responseData,
            'is_successful'  => false,
            'failure_reason' => ErrorCodes::EXTERNAL_SERVICE_UNAVAILABLE->getMessage(),
            'completed_at'   => $response->json('completed_at'),
        ]);

        $this->assertDatabaseCount('transactions', 1);

        $this->assertDatabaseHas('users', [
            'id'      => $user[0]->id,
            'balance' => $balance,
        ]);

        $this->assertDatabaseHas('users', [
            'id'      => $user[1]->id,
            'balance' => $balance,
        ]);
    }

    public function test_fail_transfer_if_external_authorizer_returns_false()
    {
        $this->externalServiceMock(false);

        $balance = rand(1, 10000);

        $user = User::factory()->common()->count(2)->create([
            'balance' => $balance,
        ]);

        $responseData = [
            'sender_id'   => $user[0]->id,
            'receiver_id' => $user[1]->id,
            'amount'      => $balance,
            'description' => 'Transfer to another user',
        ];

        $response = $this->postJson(route('transaction.store'), $responseData);

        $response
            ->assertBadRequest()
            ->assertJsonFragment([
                'is_successful'  => false,
                'failure_reason' => ErrorCodes::UNAUTHORIZED_BY_EXTERNAL_SERVICE->getMessage(),
                'sender_id'      => $user[0]->id,
                'receiver_id'    => $user[1]->id,
            ])
            ->assertJsonStructure([
                'sender_id',
                'receiver_id',
                'amount',
                'description',
                'is_successful',
                'failure_reason',
                'completed_at',
            ]);

        $this->assertDatabaseHas('transactions', [
            ...$responseData,
            'is_successful'  => false,
            'failure_reason' => ErrorCodes::UNAUTHORIZED_BY_EXTERNAL_SERVICE->getMessage(),
            'completed_at'   => $response->json('completed_at'),
        ]);

        $this->assertDatabaseCount('transactions', 1);

        $this->assertDatabaseHas('users', [
            'id'      => $user[0]->id,
            'balance' => $balance,
        ]);

        $this->assertDatabaseHas('users', [
            'id'      => $user[1]->id,
            'balance' => $balance,
        ]);
    }
}
