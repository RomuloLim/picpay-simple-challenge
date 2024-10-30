<?php

namespace Tests;

use Illuminate\Foundation\Testing\{DatabaseTransactions, TestCase as BaseTestCase};

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    //    protected $seed = true;
}
