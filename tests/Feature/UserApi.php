<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApi extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupAuth();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanGetApiForSelf()
    {
        $response = $this->actingAs($this->user, 'api')->get('/api/user');
        $response->assertStatus(200);
    }
}
