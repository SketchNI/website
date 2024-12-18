<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Blog\UpdateRequest;
use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\CategoryResource;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Response;

class BlogController
{
    public function index(Request $request): Response
    {
        if ($request->get('filter')) {
            $filter = $request->get('filter');

            if ($filter === 'deleted') {
                $posts = Post::with(['user', 'categories'])
                    ->orderByDesc('id')
                    ->onlyTrashed()
                    ->paginate(10);
            }

            if ($filter === 'unpublished') {
                $posts = Post::with(['user', 'categories'])
                    ->orderByDesc('id')
                    ->whereNull('published_at')
                    ->paginate(10);
            }
        } else {
            $posts = Post::with(['user', 'categories'])->orderByDesc('id')->paginate(10);
        }

        $posts = PostResource::collection($posts);

        $counts = [
            'deleted' => Post::onlyTrashed()->count(),
            'unpublished' => Post::whereNull('published_at')->count(),
        ];

        return inertia('Backend/Blog/Index', ['posts' => $posts, 'counts' => $counts]);
    }

    public function show(Post $post): Response
    {
        $post = Post::with(['categories'])->whereSlug($post->slug)->first();

        $categories = CategoryResource::collection(Category::all())->resolve();

        return inertia('Backend/Blog/Show', [
            'post' => $post,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        $post = Post::find($id);

        $post->title = $request->title;
        $post->slug = Str::slug($post->title);
        $post->excerpt = $request->excerpt;
        $post->content = $request->get('content');
        if ($post->published_at === null) {
            $post->published_at = $request->published ? now() : null;
        }

        if ($post->save()) {
            session()->flash('flash', ['message' => 'Blog post updated successfully.', 'type' => 'success']);
        } else {
            session()->flash('flash', ['message' => 'Blog post not updated.', 'type' => 'error']);
        }

        return redirect()->back(303);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post = Post::find($post->id);
        if ($post->delete()) {
            session()->flash('flash', ['message' => 'Blog post deleted successfully.', 'type' => 'success']);

            return redirect()->route('backend.blog.index');
        }

        session()->flash('flash', ['message' => 'Unable to delete blog post.', 'type' => 'error']);

        return redirect()->back();
    }

    public function restore(int $id): RedirectResponse
    {
        $flash = Post::withTrashed()->whereId($id)->restore()
            ? ['message' => 'Blog post restored successfully.', 'type' => 'success']
            : ['message' => 'Unable to restore blog post.', 'type' => 'error'];

        session()->flash('flash', $flash);

        return redirect()->route('backend.blog.index');
    }
}
