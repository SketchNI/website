<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Image\CreateRequest;
use App\Models\Image;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    public function index() {}

    public function store(CreateRequest $request): JsonResponse|bool
    {
        $image = new Image;
        $image->image = '/storage/'.$request->file('image')->storePublicly('/images', ['disk' => 'public']);
        if ($image->save()) {
            $image = $image->fresh();
            return response()->json([
                'image' => $image->image,
                'caption' => $image->caption,
                'alt_text' => $image->alt_text,
            ]);
        }

        return false;
    }
}
