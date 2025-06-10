<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'from' => 'string|nullable',
            'key' => 'required|string',
        ]);

        if ($request->get('key') !== config('app.upload_key')) {
            throw new UnauthorizedException('Invalid upload key.');
        }

        $caption = 'Image uploaded at '.Carbon::now()->toDayDateTimeString();

        $image = Image::create([
            'image' => '/storage/'.$request->file('image')->storePublicly('images', ['disk' => 'public']),
            'from' => $request->get('from', 'ShareX'),
            'alt_text' => $caption,
            'caption' => $caption,
        ]);

        return url($image->image);
    }

    public function show(Image $image): string
    {
        $path = str_replace('storage/storage', 'storage/app/public', storage_path($image->image));
        $mime = image_type_to_mime_type(exif_imagetype($path));

        return response()->download(
            $path,
            str_replace('/storage/images/', '', $image->image),
            ['Content-Type' => $mime.'; charset=utf-8']
        );
    }
}
