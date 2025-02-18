<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Http\Requests\Blog\ReactRequest;
use App\Models\Blog\Post;
use App\Traits\Flashable;
use App\Traits\ThrowsException;
use Illuminate\Http\RedirectResponse;

class ReactController extends Controller
{
    use Flashable;
    use ThrowsException;

    /**
     * @param  ReactRequest  $request
     * @param  Post  $post
     *
     * @return RedirectResponse
     *
     * @throws ForbiddenException
     */
    public function store(ReactRequest $request, Post $post): RedirectResponse
    {
        $this->forbidden('user::create vote');

        if ($post->toggleReaction($request->get('reaction'))) {
            $this->flash(sprintf('You have %sed this post!', $request->get('reaction')));

            activity('user')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($post)
                ->withProperties(['post' => $post, 'user' => auth()->user()])
                ->log(sprintf('%sed blog post', $request->get('reaction')));

            return redirect()->back();
        }

        $this->flash('Unable to react to post.', 'error');

        return redirect()->back();
    }
}
