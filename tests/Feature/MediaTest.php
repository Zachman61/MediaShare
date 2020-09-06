<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\MimeType;
use Tests\TestCase;
use Storage;
use Illuminate\Http\UploadedFile;

class MediaTest extends TestCase
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

        Storage::disk('media')->assertExists('i'. '/0/'.$file->hashName());

        $response->assertStatus(201);
    }

    public function testVideoUploadsWithNoName()
    {
        Storage::fake('media');

        $file = Storage::disk('public')->get('test.mp4');
        $tmp = UploadedFile::fake()->createWithContent('tmp.mp4', $file);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'file' => $tmp,
        ]);

        $response->assertStatus(201);
        Storage::disk('media')->assertExists('v'. '/0/'.$tmp->hashName());
        $response->assertStatus(201);
        $response->assertJsonCount(3);
        $response->assertJsonFragment([
            'user_id' => $this->user->id,
        ]);
    }

    public function testVideoUploadsWithName()
    {
        Storage::fake('media');

        $file = Storage::disk('public')->get('test.mp4');
        $tmp = UploadedFile::fake()->createWithContent('tmp.mp4', $file);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'title' => 'test',
            'file' => $tmp,
        ]);

        Storage::disk('media')->assertExists('v'. '/0/'.$tmp->hashName());
        $response->assertStatus(201);
        $response->assertJsonCount(3);
        $response->assertJsonFragment([
            'user_id' => $this->user->id,
            'title' => 'test'
        ]);
    }

    public function testMediaDelete()
    {
        // TODO: Replace with deletion test
        $this->assertTrue(true);
    }
}
