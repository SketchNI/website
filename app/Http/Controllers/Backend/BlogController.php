<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Blog\CreateRequest;
use App\Http\Requests\Backend\Blog\UpdateRequest;
use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\CategoryResource;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\PostHasCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BlogController
{
    public function index(Request $request): Response
    {
        if (!auth()->user()->hasPermissionTo('view blog entries')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }


        $posts = match ($request->get('filter')) {
            'deleted' => Post::isDeleted()->paginate(10),
            'unpublished' => Post::unpublished()->paginate(10),
            default => Post::normal()->paginate(10),
        };

        $counts = [
            'posts' => Post::count(),
            'deleted' => Post::isDeleted()->count(),
            'unpublished' => Post::unpublished()->count(),
        ];

        return inertia('Backend/Blog/Index', [
            'posts' => Inertia::defer(fn () => PostResource::collection($posts), 'posts'),
            'counts' => Inertia::defer(fn () => $counts, 'posts'),
            'categories' => fn () => CategoryResource::collection(Category::with('parent')->get()),
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

    public function store(CreateRequest $request): RedirectResponse
    {
        if (!auth()->user()->hasPermissionTo('write blog entry')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        if ($request->get('published') && !auth()->user()->hasPermissionTo('publish blog entry')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $post = new Post;

        $post->user_id = auth()->id();
        $post->title = $request->get('title');
        $post->slug = Str::slug($request->get('title'));
        $post->excerpt = $request->get('excerpt');
        $post->content = $request->get('content');
        if ($post->published_at === null) {
            $post->published_at = $request->get('published') ? now() : null;
        }

        if ($post->save()) {
            foreach ($request->get('categories') as $category) {
                DB::table('post_has_categories')
                    ->insert(['post_id' => $post->fresh()->id, 'cat_id' => $category]);
            }
            session()->flash('flash', ['message' => 'Blog post updated successfully.', 'type' => 'success']);
        } else {
            session()->flash('flash', ['message' => 'Blog post not updated.', 'type' => 'error']);
        }

        return redirect()->route('backend.blog.edit', $post);
    }

    public function edit(int $id): Response
    {
        if (!auth()->user()->hasPermissionTo('update blog entry')) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $post = Post::withTrashed()->find($id)->with(['user', 'categories'])->first();

        return inertia('Backend/Blog/Show', [
            'post' => $post,
            'categories' => CategoryResource::collection(Category::all())->resolve(),
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
            // Delete all categories.
            PostHasCategory::wherePostId($post->id)->delete();
            foreach ($request->get('categories') as $category) {
                /* Imho, this is nasty and I should definitely do something
                 * like spatie does in spatie/laravel-permissions where they
                 * sync roles and permissions with a user. - Sketch, 09:23pm 21/01/2025
                 */
                // Insert new categories.
                DB::table('post_has_categories')
                    ->insert(['post_id' => $post->fresh()->id, 'cat_id' => $category]);
            }

            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->withProperties(['post' => $post, 'user' => auth()->user()])
                ->log('updated blog post');

            session()->flash('flash', ['message' => 'Blog post updated successfully.', 'type' => 'success']);
        } else {
            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->withProperties(['post' => $post, 'user' => auth()->user()])
                ->log('failed to updat blog post');
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
