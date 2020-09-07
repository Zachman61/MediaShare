<?php

namespace App\Observers;

use App\Media;
use Storage;

class MediaObserver
{
    /**
     * Handle the media "created" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function created(Media $media)
    {
        //
    }

    /**
     * Handle the media "updated" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function updated(Media $media)
    {
        //
    }

    /**
     * Handle the media "deleted" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function deleted(Media $media)
    {
        Storage::disk('media')->delete($media->filename);
    }

    /**
     * Handle the media "restored" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function restored(Media $media)
    {
        //
    }

    /**
     * Handle the media "force deleted" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function forceDeleted(Media $media)
    {
        //
    }
}
