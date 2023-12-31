<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Random;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $find_user = User::where('google_id', $user->id)->first();
            if ($find_user) {
                Auth::login($find_user);
                return redirect()->intended('dashboard');
            } else {
                // TODO: password will be null then the user can add it later
                // TODO: save the user's avatar using media library
                $new_user = User::updateOrCreate(['email' => $user->email], [
                    'name' => $user->name,
                    'google_id' => $user->id,
                    'password' => encrypt(Random::generate(16)),
                ]);
                Auth::login($new_user);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
