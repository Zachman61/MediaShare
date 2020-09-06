<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Storage;
use Illuminate\Http\UploadedFile;
use Log;
use File;

class CreateMediaTest extends TestCase
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

    public function testVideoUploadsWithNoName()
    {
        $file = Storage::disk('public')->get('temp.mp4');
        $tmp = UploadedFile::fake()->createWithContent('tmp.mp4', $file);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'file' => $tmp,
        ]);

        $response->assertStatus(201);
    }

    public function testVideoUploadsWithName()
    {
        $file = Storage::disk('public')->get('temp.mp4');
        $tmp = UploadedFile::fake()->createWithContent('tmp.mp4', $file);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'title' => 'test',
            'file' => $tmp,
        ]);

        $response->assertStatus(201);
        $response->assertJsonCount(3);
        $response->assertJsonFragment([
            'user_id' => 1,
            'title' => 'test'
        ]);
    }
}
