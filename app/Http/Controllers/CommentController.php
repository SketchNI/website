<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Http\Requests\Blog\Comment\CreateRequest;
use App\Models\Blog\Post;
use App\Models\Comment;
use App\Traits\Flashable;
use App\Traits\ThrowsException;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    use Flashable;
    use ThrowsException;

    /**
     * @throws ForbiddenException
     */
    public function store(CreateRequest $request, string $slug): RedirectResponse
    {
        $this->forbidden('user::create comment');

        $comment = new Comment()
            ->setAuthor(auth()->id())
            ->setComment($request->get('comment'))
            ->setMorphs($request->get('blog')['id'], $this->resolveType($request->get('type')));

        if ($comment->save()) {
            $this->flash('Your comment has been added to the post.');

            return redirect()->back();
        }

        $this->flash('An error occurred creating your comment.', 'error');

        return redirect()->back();
    }

    /**
     * @throws ForbiddenException
     */
    public function destroy(string $slug, int $id): RedirectResponse
    {
        $this->forbidden('user::delete comment');

        $comment = Comment::find($id);

        if (
            $comment->user_id !== auth()->id() ||
            !auth()->user()->hasAnyRole(['super-admin', 'admin', 'mod'])
        ) {
            $this->flash('You do not have permission to delete this comment.', 'error');
            return redirect()->back();
        }

        if ($comment->delete()) {
            $this->flash('Your comment has been deleted.');
            return redirect()->back();
        }

        $this->flash('An error occurred deleting your comment.', 'error');

        return redirect()->back();
    }

    private function resolveType(string $type): string
    {
        return match ($type) {
            'post' => Post::class,
        };
    }
}
