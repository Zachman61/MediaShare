<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Storage;
use Illuminate\Http\UploadedFile;
use Log;

class UploadMedia extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupAuth();
    }


    public function testImageUploadWorks()
    {
        Storage::fake('media');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'file' => $file,
        ]);

        Log::debug($response->getContent());

        $response->assertStatus(201);
    }

    public function testVideoUploadWorks()
    {
        Storage::fake('media');

        $file = UploadedFile::fake()->create('file.mp4', 350, 'video/mp4');

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'file' => $file,
        ]);

        Log::debug($response->content());

        $response->assertStatus(201);
    }
}
