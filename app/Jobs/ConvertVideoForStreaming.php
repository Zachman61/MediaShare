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
        if ($media->type !== 'video')
        {
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

        $name = \File::name($this->media->filename);
        $convertedName = $name . '.' .  \File::extension($this->media->filename);
        // open the uploaded video from the right disk...
        FFMpeg::fromDisk('media')
            ->open($this->media->filename)

            // add the 'resize' filter...
            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(960, 540));
            })

            // call the 'export' method...
            ->export()

            // tell the MediaExporter to which disk and in which format we want to export...
            ->toDisk('media')
            ->inFormat($lowBitrateFormat)
            ->save($convertedName);

        \Storage::disk('media')->delete($this->media->filename);
        $this->media->update([
            'status' => 'ready',
            'filename' => $convertedName
        ]);
    }

    private function getCleanFileName($filename){
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) . '.mp4';
    }
}
