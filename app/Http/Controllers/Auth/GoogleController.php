<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    // Redirect the user to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle the user coming back from Google
    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
    } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
        return redirect('/')->with('error', 'Google login failed. Please try again.');
    }

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ]
    );

    Auth::login($user);

    // Check if user had a pending review
    if (session()->has('pending_review')) {

        $review = session('pending_review');

        Review::create([
            'business_id' => $review['business_id'],
            'user_id' => $user->id,
            'rating' => $review['rating'],
            'comment' => $review['comment'],
        ]);

        session()->forget('pending_review');

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    return redirect('/');
}
}
