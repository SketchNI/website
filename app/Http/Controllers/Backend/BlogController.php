<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\ForbiddenException;
use App\Http\Requests\Backend\Blog\CreateRequest;
use App\Http\Requests\Backend\Blog\UpdateRequest;
use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\TagResource;
use App\Models\Post;
use App\Traits\Flashable;
use App\Traits\ThrowsException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Tags\Tag;
use Throwable;

class BlogController
{
    use Flashable;
    use ThrowsException;

    /**
     * Render the blog post index page.
     *
     *
     *
     * @throws ForbiddenException
     */
    public function index(Request $request): Response
    {
        $this->forbidden('view blog entries');

        return inertia('Backend/Blog/Index', [
            'posts' => Inertia::defer(fn() => $this->resolvePosts($request->get('filter')), 'posts'),
            'counts' => Inertia::defer(fn() => [
                'posts' => Post::published()->count(),
                'unpublished' => Post::unpublished()->count(),
                'deleted' => Post::onlyTrashed()->count(),
            ], 'posts'),
            'categories' => fn() => Tag::whereType('blog')->get(),
        ]);
    }

    /**
     * Render the blog post create page.
     *
     *
     * @throws ForbiddenException
     */
    public function create(): Response
    {
        $this->forbidden('write blog entry');

        return inertia('Backend/Blog/Create', [
            'tags' => Tag::whereType('post')->get(),
        ]);
    }

    /**
     * Create a new blog post.
     */
    public function store(CreateRequest $request): RedirectResponse
    {
        $redirect = null;
        try {
            $this->forbidden('write blog entry');

            $post = new Post()
                ->setAuthor($request->user()->id)
                ->setTitle($request->get('title'))
                ->setExcerpt($request->get('excerpt'))
                ->setContent($request->get('content'))
                ->setPublishedAt($request->get('published'));

            if (!$post->save()) {
                $this->flash('Blog post not created.', 'error');
            }

            $post->attachTags($request->get('tags'));

            activity('admin')
                ->by($request->user())
                ->causedBy($request->user())
                ->on($post)
                ->withProperties(['post' => $post, 'user' => $request->user()])
                ->log('created blog post');

            $this->flash('Blog post created successfully.');

            return redirect()->route('backend.blog.edit', $post);
        } catch (ForbiddenException $e) {
            $this->flash($e->getMessage(), 'error');

            return redirect()->back();
        } catch (Throwable $e) {
            $this->flash($e->getMessage(), 'error');

            return redirect()->back();
        }
    }

    /**
     * Render the edit blog post page.
     *
     *
     *
     * @throws ForbiddenException
     */
    public function edit(Post $post): Response
    {
        $this->forbidden('update blog entry', $post);

        $post = new PostResource(Post::withTrashed()->find($post->id))->resolve();

        return inertia('Backend/Blog/Show', [
            'post' => $post,
            'tags' => TagResource::collection(Tag::whereType('post')->get())->resolve(),
        ]);
    }

    /**
     * Update a blog post.
     *
     *
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

        $post->syncTags($request->get('tags'))->save();

        $post->save();

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
     *
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
     *
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
            'deleted' => Post::onlyTrashed()->paginate(10),
            'unpublished' => Post::unpublished()->paginate(10),
            default => Post::published()->paginate(10),
        });
    }
}
