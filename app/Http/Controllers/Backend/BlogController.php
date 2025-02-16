<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\ForbiddenException;
use App\Http\Requests\Backend\Blog\CreateRequest;
use App\Http\Requests\Backend\Blog\UpdateRequest;
use App\Http\Resources\Blog\CategoryResource;
use App\Http\Resources\Blog\PostResource;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\PostHasCategory;
use App\Traits\Flashable;
use App\Traits\ThrowsException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class BlogController
{
    use Flashable;
    use ThrowsException;

    /**
     * Render the blog post index page.
     *
     * @param  Request  $request
     *
     * @return Response
     *
     * @throws ForbiddenException
     */
    public function index(Request $request): Response
    {
        $this->forbidden('view blog entries');

        $post = new Post;

        return inertia('Backend/Blog/Index', [
            'posts' => Inertia::defer(fn () => $this->resolvePosts($request->get('filter')), 'posts'),
            'counts' => Inertia::defer(fn () => [
                'posts' => $post->isNotDeleted()->published()->count(),
                'unpublished' => $post->isNotDeleted()->unpublished()->count(),
                'deleted' => $post->isDeleted()->count(),
            ], 'posts'),
            'categories' => fn () => CategoryResource::collection(Category::with('parent')->get()),
        ]);
    }

    /**
     * Render the blog post create page.
     *
     * @return Response
     *
     * @throws ForbiddenException
     */
    public function create(): Response
    {
        $this->forbidden('write blog entry');

        return inertia('Backend/Blog/Create', [
            'categories' => CategoryResource::collection(Category::all())->resolve(),
        ]);
    }

    /**
     * Create a new blog post.
     *
     * @param  CreateRequest  $request
     *
     * @return RedirectResponse
     *
     * @throws ForbiddenException
     */
    public function store(CreateRequest $request): RedirectResponse
    {
        $this->forbidden('write blog entry');

        $post = new Post;
        $post->setAuthor(auth()->id())
            ->setTitle($request->get('title'))
            ->setExcerpt($request->get('excerpt'))
            ->setContent($request->get('content'));

        if (auth()->user()->hasPermissionTo('publish blog entry')) {
            $post->setPublishedAt($request->get('published'));
        }

        if (!$post->save()) {
            $this->flash('Blog post not created.', 'error');
        }

        foreach ($request->get('categories') as $category) {
            DB::table('post_has_categories')
                ->insert(['post_id' => $post->fresh()->id, 'cat_id' => $category]);
        }

        activity('admin')
            ->by(auth()->user())
            ->causedBy(auth()->user())
            ->on($post)
            ->withProperties(['post' => $post, 'user' => auth()->user()])
            ->log('created blog post');

        $this->flash('Blog post created successfully.');

        return redirect()->route('backend.blog.edit', $post);
    }

    /**
     * Render the edit blog post page.
     *
     * @param  Post  $post
     *
     * @return Response
     *
     * @throws ForbiddenException
     */
    public function edit(Post $post): Response
    {
        $this->forbidden('update blog entry', $post);

        $post = Post::withTrashed()->with(['user', 'categories'])->find($post->id);

        return inertia('Backend/Blog/Show', [
            'post' => $post,
            'categories' => CategoryResource::collection(Category::all())->resolve(),
        ]);
    }

    /**
     * Update a blog post.
     *
     * @param  UpdateRequest  $request
     * @param  Post  $post
     *
     * @return RedirectResponse
     *
     * @throws ForbiddenException
     */
    public function update(UpdateRequest $request, Post $post): RedirectResponse
    {
        $this->forbidden('update blog entry', $post);

        $post = Post::find($post->id)
            ->setTitle($request->get('title'))
            ->setExcerpt($request->get('excerpt'))
            ->setContent($request->get('content'))
            ->setPublishedAt($request->get('published'));

        if (!$post->save()) {
            session()->flash('flash', ['message' => 'Blog post not updated.', 'type' => 'error']);
        }

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
            ->on($post)
            ->withProperties(['post' => $post, 'user' => auth()->user()])
            ->log('updated blog post');

        session()->flash('flash', ['message' => 'Blog post updated successfully.', 'type' => 'success']);

        return redirect()->back();
    }

    /**
     * Delete a blog post.
     *
     * @param  int  $id
     *
     * @return RedirectResponse
     *
     * @throws ForbiddenException
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->forbidden('delete blog entry');

        $post = Post::find($id);

        if ($post->delete()) {
            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($post)
                ->withProperties(['post' => $post, 'user' => auth()->user()])
                ->log('deleted blog post');

            session()->flash('flash', ['message' => 'Blog post deleted successfully.', 'type' => 'success']);

            return redirect()->route('backend.blog.index');
        }

        session()->flash('flash', ['message' => 'Unable to delete blog post.', 'type' => 'error']);

        return redirect()->back();
    }

    /**
     * Restore a blog post.
     *
     * @param  int  $id
     *
     * @return RedirectResponse
     *
     * @throws ForbiddenException
     */
    public function restore(int $id): RedirectResponse
    {
        $this->forbidden('restore blog entry');

        if (!Post::withTrashed()->find($id)->restore()) {
            $this->flash('Unable to restore blog post.', 'error');
        }

        $this->flash('Blog post restored successfully.');

        activity('admin')
            ->by(auth()->user())
            ->causedBy(auth()->user())
            ->on($post = Post::withTrashed()->find($id))
            ->withProperties(['post' => $post, 'user' => auth()->user()])
            ->log('restored blog post');

        return redirect()->route('backend.blog.edit', ['post' => $id]);
    }

    /* Private methods */
    private function resolvePosts(?string $filter): AnonymousResourceCollection
    {
        return PostResource::collection(match ($filter) {
            'deleted' => Post::onlyTrashed()->with(['user', 'categories'])->paginate(10),
            'unpublished' => Post::unpublished()->paginate(10),
            default => Post::published()->isNotDeleted()->paginate(10),
        });
    }
}
