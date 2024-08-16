<?php

namespace Tests\Feature\Transactions;

use App\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_can_transfer_to_another_user()
    {
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
    }

    public function test_cannot_transfer_if_user_has_no_balance()
    {
    }

    public function test_fail_transfer_if_external_authorizer_is_down()
    {
    }

    public function test_fail_transfer_if_external_authorizer_returns_false()
    {
    }

    public function test_revert_transfer_when_found_errors()
    {
    }
}
