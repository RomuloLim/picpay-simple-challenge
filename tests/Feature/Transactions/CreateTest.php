<?php

namespace Tests\Feature\Transactions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_can_transfer_to_another_user()
    {

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
