<?php

namespace Tests\Feature;

use App\Jobs\ConvertVideoForStreaming;
use App\Jobs\CreateThumbnailFromVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\MimeType;
use Queue;
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

        Storage::disk('media')->assertExists('i' . '/0/' . $file->hashName());

        $response->assertStatus(201);
    }

    public function testVideoUploadsWithNoName()
    {
        Storage::fake('media');
        Storage::fake('thumbnails');
        Queue::fake();
        Queue::assertNothingPushed();


        $file = Storage::disk('public')->get('test.mp4');
        $tmp = UploadedFile::fake()->createWithContent('tmp.mp4', $file);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'file' => $tmp,
        ]);

        Queue::assertPushedWithChain(ConvertVideoForStreaming::class, [
            CreateThumbnailFromVideo::class
        ]);
    }

    public function testVideoUploadsWithName()
    {
        Storage::fake('media');
        Storage::fake('thumbnails');
        Queue::fake();
        Queue::assertNothingPushed();

        $file = Storage::disk('public')->get('test.mp4');
        $tmp = UploadedFile::fake()->createWithContent('tmp.mp4', $file);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/media', [
            'title' => 'test',
            'file' => $tmp,
        ]);

        Queue::assertPushedWithChain(ConvertVideoForStreaming::class, [
            CreateThumbnailFromVideo::class
        ]);
    }

    public function testMediaDelete()
    {
        // TODO: Replace with deletion test
        $this->assertTrue(true);
    }
}
