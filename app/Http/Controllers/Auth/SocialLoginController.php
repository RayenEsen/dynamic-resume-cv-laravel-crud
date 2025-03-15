<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class SocialLoginController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find or create the user
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(Str::random(16)), // Random password for social login
                ]
            );

            // Log in the user
            Auth::login($user);

            // Redirect to set password page if no password is set
            if (!$user->password || $user->password == '') {
                return redirect('/set-password'); // Redirect to a password setup page
            }

            return redirect('/dashboard'); // Redirect to the dashboard or home page
        } catch (\Exception $e) {
            Log::error('Google OAuth Error:', ['error' => $e->getMessage()]);
            return redirect('/login')->withErrors('Failed to login with Google. Please try again.');
        }
    }

    // Redirect to GitHub
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    // Handle GitHub callback
    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            // Find or create the user
            $user = User::firstOrCreate(
                ['email' => $githubUser->getEmail()],
                [
                    'name' => $githubUser->getName() ?? $githubUser->getNickname(), // Use nickname if name is null
                    'password' => bcrypt(Str::random(16)), // Random password for social login
                ]
            );

            // Log in the user
            Auth::login($user);

            // Redirect to set password page if no password is set
            if (!$user->password || $user->password == '') {
                return redirect('/set-password'); // Redirect to a password setup page
            }

            return redirect('/dashboard'); // Redirect to the dashboard or home page
        } catch (\Exception $e) {
            Log::error('GitHub OAuth Error:', ['error' => $e->getMessage()]);
            return redirect('/login')->withErrors('Failed to login with GitHub. Please try again.');
        }
    }

    // Show set password form
    public function showSetPasswordForm()
    {
        return view('auth.set-password');
    }

    // Handle password setup
    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        // Update the user's password
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect('/dashboard')->with('success', 'Password set successfully!');
    }
}