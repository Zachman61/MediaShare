<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ConvertVideoForStreaming;
use App\Jobs\CreateThumbnailFromVideo;
use App\Media;
use App\User;
use App\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaController extends Controller
{
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

        echo "test\n";
        echo $request->file('file')->getClientOriginalName() ."\n";

        $title = !empty($data['title']) ? $data['title'] : '';

        /** @var UploadedFile $file */
        $file = $request->file('file');

        if (!$file->isValid())
        {
            echo "invalid file\n";
            \Log::error('Invalid file');
            response()->json([
                'error' => 'File failed to upload.'
            ], 422);
        }

        $mime = $file->getMimeType();

        if (is_null($mime))
        {
            echo "busted mine \n";
            response()->json([
                'error' => 'Could not parse file type.'
            ], 422);
        }

        if (str_contains($mime ?: '', 'image/'))
        {
            $image = $this->uploadImage($request, $file, $title);

            return response()->json($image->only('title', 'user_id', 'link'), 201);
        }
        else if (str_contains($mime ?: '', 'video/'))
        {
            $video = $this->uploadVideo($request, $file, $title);
            echo "video made\n";

            ConvertVideoForStreaming::withChain([
                new CreateThumbnailFromVideo($video),
            ])->dispatch($video);

            echo "post queue\n";

            return response()->json($video->only('title', 'user_id', 'link'), 201);
        }
        echo "Missed both \n";
        return response()->json([
            'status' => 'Failed to process upload'
        ], 422);
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

        $image->filename = $this->handleFile($request->user(), $file, 'i', $image);

        $image->saveOrFail();

        return $image;
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

    public function uploadVideo(Request $request, UploadedFile  $file, string $title = '') : Media
    {
        $video = new Media([
            'title' => $title ?: "{$request->user()->username}'s video",
            'type' => 'video',
            'user_id' => $request->user()->id,
            'hash' => Str::random(8) . '.' . $file->getClientOriginalExtension()
        ]);

        $video->save();

        $video->filename = $this->handleFile($request->user(), $file, 'tmp');

        $video->saveOrFail();

        return $video;
    }
}
