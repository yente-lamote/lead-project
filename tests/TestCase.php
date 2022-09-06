<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    protected $seed = true;

    protected function signIn($user=null){
        $user=$user ?? User::factory()->create();
        $this->actingAs($user);
        return $user;
    }
}
