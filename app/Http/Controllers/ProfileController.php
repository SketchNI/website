<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Http\Requests\ProfileUpdateRequest;
use App\Traits\Flashable;
use App\Traits\ThrowsException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    use Flashable;
    use ThrowsException;

    /**
     * Display the user's profile form.
     *
     * @throws ForbiddenException
     */
    public function edit(Request $request): Response
    {
        $this->forbidden('user::view profile');

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @throws ForbiddenException
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->forbidden('user::update profile');

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     *
     * @throws ForbiddenException
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->forbidden('user::delete profile');

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
