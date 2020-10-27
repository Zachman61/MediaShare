<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected User $user;

    public function setupAuth($admin = false)
    {
        $user = User::factory()->create([
            'is_admin' => $admin
        ]);

        $this->user = $user;
    }
}
