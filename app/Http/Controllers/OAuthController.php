<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Random;

class OAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            $find_user = User::where($provider . '_id', $user->id)->first();
            if ($find_user) {
                Auth::login($find_user);
                return redirect()->intended('dashboard');
            } else {
                // TODO: password will be null then the user can add it later
                // TODO: save the user's avatar using media library
                $new_user = User::updateOrCreate(['email' => $user->email], [
                    'name' => $user->name,
                    $provider . '_id' => $user->id,
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
