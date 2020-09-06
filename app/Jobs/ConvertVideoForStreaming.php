<?php

namespace App\Jobs;

use App\Media;
use FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Media $media;

    /**
     * Create a new job instance.
     *
     * @param Media $media
     * @throws \Exception
     */
    public function __construct(Media $media)
    {
        if ($media->type !== 'video') {
            throw new \Exception('Images cannot be converted for streaming.');
        }

        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create a video format...
        $lowBitrateFormat = (new X264('libmp3lame', 'libx264'))->setKiloBitrate(500);
        $convertedName = 'v' . DIRECTORY_SEPARATOR . floor($this->media->user_id / 10) . DIRECTORY_SEPARATOR . $this->media->id . '.' . \File::extension((string)$this->media->filename);

        FFMpeg::fromDisk('media')
            ->open($this->media->filename)
            ->addFilter(function (FFMpeg\Filters\Video\VideoFilters $filters) {
                $filters->resize(new Dimension(960, 540));
            })
            ->export()
            ->toDisk('media')
            ->inFormat($lowBitrateFormat)
            ->save($convertedName);

        \Storage::disk('media')->delete((string)$this->media->filename);
        $this->media->update([
            'status' => 'ready',
            'filename' => $convertedName
        ]);
        FFMpeg::cleanupTemporaryFiles();
    }
}
