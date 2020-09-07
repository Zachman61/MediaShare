<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ConvertVideoForStreaming;
use App\Jobs\CreateThumbnailFromVideo;
use App\Media;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function show(Media $media) : JsonResponse
    {
        return response()->json($media);
    }

    public function create(Request $request) : JsonResponse
    {
        $data = $this->validate($request, [
            'title' => 'string|min:3',
            'file' => [
                'file',
                'required',
                'mimes:jpeg,bmp,png,gif,mp4,webm,m4a',
                'max:'. config('media.max_file_size')
            ]
        ]);

        $title = !empty($data['title']) ? $data['title'] : '';

        /** @var UploadedFile $file */
        $file = $request->file('file');

        if (!$file->isValid())
        {
            \Log::error('Invalid file');
            response()->json([
                'error' => 'File failed to upload.'
            ], 422);
        }

        $mime = $file->getMimeType();

        if (is_null($mime))
        {
            response()->json([
                'error' => 'Could not parse file type.'
            ], 422);
        }

        if (str_contains($mime ?: '', 'image/'))
        {
            $image = $this->uploadImage($request, $file, $title);

            return response()->json($image, 201);
        }
        else if (str_contains($mime ?: '', 'video/'))
        {
            $video = $this->uploadVideo($request, $file, $title);
            ConvertVideoForStreaming::withChain([
                new CreateThumbnailFromVideo($video),
            ])->dispatch($video);

            return response()->json($video, 201);
        }

        return response()->json([
            'status' => 'Failed to process upload'
        ], 422);
    }

    public function delete(Request $request, Media $media) : JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user->can('delete-media', $media))
        {
            return response()->json([
                'error' => 'You are not allowed to modify this resource.'
            ], 403);
        }

        if ($media->type == 'video' && $media->status == 'processing')
        {
            return response()->json([
                'error' => 'You cannot delete a video that is processing.'
            ], 500);
        }

        $media->delete();

        return response()->json([], 204);
    }

    public function uploadImage(Request $request, UploadedFile $file, string $title = '') : Media
    {
        $image = new Media([
            'title' => $title ?: "{$request->user()->username}'s image",
            'type' => 'image',
            'user_id' => $request->user()->id,
            'hash' => \Str::random(16) . '.' . $file->getClientOriginalExtension(),
            'status' => 'ready'
        ]);

        $image->filename = $this->handleFile($request->user(), $file, 'i');

        $image->saveOrFail();

        return $image;
    }

    public function uploadVideo(Request $request, UploadedFile  $file, string $title = '') : Media
    {
        $video = new Media([
            'title' => $title ?: "{$request->user()->username}'s video",
            'type' => 'video',
            'status' => 'processing',
            'user_id' => $request->user()->id,
            'hash' => Str::random(8) . '.' . $file->getClientOriginalExtension()
        ]);

        $video->save();

        $video->filename = $this->handleFile($request->user(), $file, 'tmp');

        $video->saveOrFail();

        return $video;
    }

    /**
     * @param User $user
     * @param UploadedFile $file
     * @param string $folder
     * @param Media $media
     * @return string|false
     */
    public function handleFile(User $user, UploadedFile $file, string $folder)
    {
        return $file->store($folder . DIRECTORY_SEPARATOR . floor($user->id / 10), 'media');
    }
}
