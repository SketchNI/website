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
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Tags\Tag;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BlogController
{
    public function index(Request $request): Response
    {
        if (!auth()->user()->hasPermissionTo('view blog entries')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $posts = Post::with('user')->orderByDesc('id')->paginate(10);

        if ($request->get('filter')) {
            $filter = $request->get('filter');

            if ($filter === 'deleted') {
                $posts = Post::with('user')
                    ->orderByDesc('id')
                    ->onlyTrashed()
                    ->paginate(10);
            }

            if ($filter === 'unpublished') {
                $posts = Post::with('user')
                    ->orderByDesc('id')
                    ->whereNull('published_at')
                    ->paginate(10);
            }
        }

        $counts = [
            'deleted' => Post::onlyTrashed()->count(),
            'unpublished' => Post::whereNull('published_at')->count(),
        ];

        return inertia('Backend/Blog/Index', [
            'posts' => Inertia::defer(fn () => PostResource::collection($posts)),
            'counts' => Inertia::defer(fn () => $counts),
            'categories' => fn () => CategoryResource::collection(Category::all()),
        ]);
    }

    public function create(): Response
    {
        $categories = CategoryResource::collection(
            Category::all(),
        )->resolve();

        return inertia('Backend/Blog/Create', [
            'categories' => $categories,
        ]);
    }

    public function edit(int $id): Response
    {
        if (!auth()->user()->hasPermissionTo('update blog entry')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $post = Post::withTrashed()->find($id)->with('user')->first();

        activity('admin')
            ->by(auth()->user())
            ->causedBy(auth()->user())
            ->withProperties(['post' => $post, 'user' => auth()->user()])
            ->log(sprintf('%s updated blog post', auth()->user()->name));

        return inertia('Backend/Blog/Show', [
            'post' => $post,
            'categories' => Tag::whereType('blog')->get(),
        ]);
    }

    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        if (!auth()->user()->hasPermissionTo('update blog entry')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        if ($request->get('published') && !auth()->user()->hasPermissionTo('publish blog entry')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $post = Post::find($id);

        $post->title = $request->get('title');
        $post->slug = Str::slug($post->get('title'));
        $post->excerpt = $request->get('excerpt');
        $post->content = $request->get('content');
        if ($post->published_at === null) {
            $post->published_at = $request->get('published') ? now() : null;
        }

        if ($post->save()) {
            session()->flash('flash', ['message' => 'Blog post updated successfully.', 'type' => 'success']);
        } else {
            session()->flash('flash', ['message' => 'Blog post not updated.', 'type' => 'error']);
        }

        return redirect()->back(303);
    }

    public function destroy(int $id): RedirectResponse
    {
        if (!auth()->user()->hasPermissionTo('delete blog entry')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        if (Post::find($id)->delete()) {
            session()->flash('flash', ['message' => 'Blog post deleted successfully.', 'type' => 'success']);

            return redirect()->route('backend.blog.index');
        }

        session()->flash('flash', ['message' => 'Unable to delete blog post.', 'type' => 'error']);

        return redirect()->back();
    }

    public function restore(int $id): RedirectResponse
    {
        if (!auth()->user()->hasPermissionTo('restore blog entry')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $flash = Post::withTrashed()->whereId($id)->restore()
            ? ['message' => 'Blog post restored successfully.', 'type' => 'success']
            : ['message' => 'Unable to restore blog post.', 'type' => 'error'];

        session()->flash('flash', $flash);

        return redirect()->route('backend.blog.index');
    }
}
