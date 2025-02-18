<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\PostResource;
use App\Models\Post;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    public function __invoke(): Response
    {
        $posts = json_decode(json_encode(PostResource::collection(Post::published()->get())->resolve()));
        $last_updated = Post::orderByDesc('id')->first()->created_at;

        return response()
            ->view('feed', compact('posts', 'last_updated'))
            ->header('Content-Type', 'application/xml');
    }
}
