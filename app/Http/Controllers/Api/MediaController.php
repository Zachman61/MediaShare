<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Media;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class MediaController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'file' => [
                'file',
                'required',
                'mimes:jpeg,bmp,png,gif,mp4,webm,m4a',
                'max:'. config('media.max_file_size')
            ]
        ]);

        /** @var UploadedFile $file */
        $file = $request->file('file');

        if (!$file->isValid())
        {
            response()->json([
                'error' => 'File failed to upload.'
            ], 422);
        }

        $mime = $file->getMimeType();

        if (str_contains($mime, 'image/'))
        {
            $image = $this->uploadImage($request, $file);

            return response()->json([
                'status' => 'image uploaded',
                'filename' => url('/m/'. $image->hash)
            ], 201);
        }
        else if (str_contains($mime, 'video/'))
        {
            $video = $this->uploadVideo($request, $file);

            return response()->json([
                'status' => 'video uploaded'
            ], 201);
        }

        return response()->json([
            'status' => 'Failed to process upload'
        ], 422);
    }

    public function uploadImage(Request $request, UploadedFile $file) : Media
    {
        $image = new Media([
            'title' => "{$request->user()->username}'s image",
            'type' => 'image',
            'user_id' => $request->user()->id,
            'hash' => \Str::random(16) . '.' . $file->getClientOriginalExtension(),
            'status' => 'ready',
            'filename' => $this->handleFile($request, $file, 'i')
        ]);

        $image->saveOrFail();

        return $image;
    }

    public function handleFile(Request $request, UploadedFile $file, string $folder) : string
    {
        return $file->store($folder . DIRECTORY_SEPARATOR . floor($request->user()->Id / 10), 'media');
    }

    public function uploadVideo(Request $request, UploadedFile  $file) : Media
    {
        $video = new Media([
            'title' => "{$request->user()->username}'s video",
            'type' => 'image',
            'user_id' => $request->user()->id,
            'hash' => \Str::random(16) . '.' . $file->getClientOriginalExtension(),
            'filename' => $this->handleFile($request, $file, 'i')
        ]);

        $video->saveOrFail();

        return $video;
    }
}
