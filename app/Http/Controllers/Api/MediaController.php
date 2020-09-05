<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Media;
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
            abort(500, 'File upload failed');
        }

        $mime = $file->getMimeType();

        if (str_contains($mime, 'image/'))
        {
            $path = $file->store('public/'. floor($request->user()->Id / 1000));

            $image = new Media();
            $image->user_id = $request->user()->id;
            $image->hash = \Str::random(16) . '.' . $file->getClientOriginalExtension();
            $image->filename = $path;
            $image->save();

            return response()->json([
                'status' => 'image uploaded',
                'filename' => url('/m/'. $image->hash)
            ], 201);
        }
        else if (str_contains($mime, 'video/'))
        {
            return response()->json([
                'status' => 'video uploaded'
            ], 201);
        }

        return response()->json([
            'status' => 'Failed to upload'
        ], 500);
    }
}
