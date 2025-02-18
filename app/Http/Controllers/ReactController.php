<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidReactionException;
use App\Http\Requests\Blog\ReactRequest;
use App\Models\Post;
use App\Traits\Flashable;
use App\Traits\ThrowsException;
use Illuminate\Http\RedirectResponse;

class ReactController extends Controller
{
    use Flashable;
    use ThrowsException;

    /**
     * @throws ForbiddenException
     */
    public function store(ReactRequest $request, Post $post): RedirectResponse
    {
        $this->forbidden('user::create vote');

        try {
            $post->toggleReaction($request->get('reaction'));
            $reaction = $this->resolveReactionVerb($request->get('reaction'));
            $this->flash("You have $reaction this post!");

            activity('user')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($post)
                ->withProperties(['post' => $post, 'user' => auth()->user()])
                ->log("$reaction blog post.");

            return redirect()->back();
        } catch (InvalidReactionException $e) {
            $this->flash($e->getMessage(), 'error');

            return redirect()->back();
        }
    }

    /**
     * @throws InvalidReactionException
     */
    private function resolveReactionVerb(string $reaction): string
    {
        return match ($reaction) {
            'upvote' => 'upvoted',
            'downvote' => 'downvoted',
            'poop' => 'shat on',
            'heart' => 'loved',
            default => throw new InvalidReactionException("Reaction $reaction does not exist."),
        };
    }
}
