<?php

namespace App\Http\Controllers;

use App\Models\Blog\Post;

class FeedController extends Controller
{
    public function __invoke()
    {
        $posts = Post::published()->get();
        $last_updated = Post::orderByDesc('id')->first()->created_at;

        return response()
            ->view('feed', compact('posts', 'last_updated'))
            ->header('Content-Type', 'application/xml');
    }
}
