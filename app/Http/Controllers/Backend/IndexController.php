<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class IndexController extends Controller
{
    public function __invoke(): Response
    {
        if (!auth()->user()->hasAnyRole(['mod', 'admin', 'super-admin'])) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $counts = [
            ['name' => 'Users', 'value' => User::count(), 'unit' => null],
            ['name' => 'Posts', 'value' => Post::count(), 'unit' => null],
            ['name' => 'Images', 'value' => Image::count(), 'unit' => null],
            ['name' => 'Comments', 'value' => Comment::count(), 'unit' => null],
        ];

        return inertia('Backend/Index', compact('counts'));
    }
}
