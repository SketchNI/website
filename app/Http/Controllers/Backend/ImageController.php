<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Image\CreateRequest;
use App\Http\Requests\Backend\Image\UpdateRequest;
use App\Models\Image;
use App\Traits\Flashable;
use App\Traits\ThrowsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ImageController extends Controller
{
    use Flashable;
    use ThrowsException;

    /**
     * Render the images index page.
     *
     * @throws ForbiddenException
     */
    public function index(): Response
    {
        $this->forbidden('view images');

        if (auth()->user()->hasRole('super-admin')) {
            return inertia('Backend/Images/Index', [
                'images' => Image::orderByDesc('created_at')->get(),
            ]);
        }

        return inertia('Backend/Images/Index', [
            'images' => Image::orderByDesc('created_at')->whereFrom('web')->get(),
        ]);
    }

    /**
     * Uploads an image.
     *
     * @throws ForbiddenException
     */
    public function store(CreateRequest $request): JsonResponse|bool
    {
        $this->forbidden('create image');

        $image = new Image();
        $image->image = '/storage/'.$request->file('image')->storePublicly('/images', ['disk' => 'public']);
        if ($image->save()) {
            $image = $image->fresh();

            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($image)
                ->withProperties(['image' => $image, 'user' => auth()->user()])
                ->log('created image.');

            return response()->json([
                'image' => $image->image,
                'caption' => $image->caption,
                'alt_text' => $image->alt_text,
            ]);
        }

        return false;
    }

    /**
     * Update an image.
     *
     * @throws ForbiddenException
     */
    public function update(UpdateRequest $request, Image $image): JsonResponse
    {
        $this->forbidden('update image');

        $image = Image::find($image->id)
            ->setCaption($request->get('caption'))
            ->setAltText($request->get('alt_text'));

        if ($image->save()) {
            $image = $image->fresh();

            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->withProperties(['image' => $image, 'user' => auth()->user()])
                ->log('updated an image.');

            return response()->json([
                'message' => 'Image has been updated.',
                'type' => 'success',
            ]);
        }

        return response()->json([
            'message' => 'Unable to update image.',
            'type' => 'error',
        ], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Deletes an image.
     *
     * @throws ForbiddenException
     */
    public function destroy(Image $image): RedirectResponse
    {
        $this->forbidden('delete image');

        if (!$image->delete()) {
            $this->flash('Unable to delete image.');

            return redirect()->back();
        }

        activity('admin')
            ->by(auth()->user())
            ->causedBy(auth()->user())
            ->withProperties(['user' => auth()->user()])
            ->log('deleted an image.');

        Storage::disk('public')->delete($image->image);

        $this->flash('Image has been deleted.');

        return redirect()->back();
    }
}
