<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Flashable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class LoginController extends Controller
{
    use Flashable;

    public function index(string $driver): RedirectResponse|SymfonyRedirectResponse
    {
        return match ($driver) {
            'github' => Socialite::driver($driver)->redirect(),
            default => $this->back(),
        };
    }

    private function back(): RedirectResponse
    {
        $this->flash('Unsupported OAuth driver.', 'error');

        return redirect()->back();
    }

    public function callback(string $driver): RedirectResponse
    {
        $socialite_user = Socialite::driver($driver)->user();

        $user = User::updateOrCreate(
            ['email' => $socialite_user->email],
            [
                'name' => $socialite_user->getName(),
                'email' => $socialite_user->getEmail(),
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('user');

        auth()->login($user->fresh());

        return redirect()->route('home');
    }
}
