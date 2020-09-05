<?php

namespace App\Jobs;

use App\Media;
use FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateThumbnailFromVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Media $media;

    /**
     * Create a new job instance.
     *
     * @param Media $media
     */
    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        FFMpeg::fromDisk('media')
            ->open($this->media->filename)
            ->getFrameFromSeconds(0)
            ->export()
            ->toDisk('thumbnails')
            ->save($this->media.'.png');
    }
}
