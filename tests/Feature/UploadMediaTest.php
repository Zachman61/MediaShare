<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Storage;
use Illuminate\Http\UploadedFile;
use Log;

class UploadMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupAuth();
    }

    public function testNoFileFails()
    {
        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media');

        $response->assertStatus(422);
    }

    public function testWrongFileTypeFails()
    {
        $file = UploadedFile::fake()->create('test.pdf', 10, 'application/pdf');

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'file' => $file
        ]);

        $response->assertStatus(422);
    }

    public function testImageUploadWorks()
    {
        Storage::fake('media');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'file' => $file,
        ]);

        $response->assertStatus(201);
    }

    public function testVideoUploadWorks()
    {
        Storage::fake('media');

        $file = UploadedFile::fake()->create('file.mp4', 350, 'video/mp4');

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'file' => $file,
        ]);

        $response->assertStatus(201);
    }
}
