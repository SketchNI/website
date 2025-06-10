<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\TagResource;
use App\Models\Post;
use App\Types\TagType;
use Inertia\Response;
use Spatie\Tags\Tag;

class TagController extends Controller
{
    public function __invoke(string $tag, TagType $type): Response
    {
        $posts = PostResource::collection(Post::withAnyTags($tag, 'post')->whereNotNull('published_at')->orderByDesc('published_at')->paginate(12));

        $current = Tag::query()->where('slug->en', $tag)->where('type', 'post')->first()->name;

        $tags = TagResource::collection(Tag::withType('post')->get())->resolve();

        return inertia('Tag/Show', compact('posts', 'tags', 'current'));
    }
}
