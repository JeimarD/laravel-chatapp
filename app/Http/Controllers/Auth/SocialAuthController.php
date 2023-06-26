<?php

namespace App\Http\Controllers\Auth;

use App\Events\LoggedIn;
use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Predis\Client as PredisClient;

class SocialAuthController extends Controller
{
    public function redirectToProvider($driver)
    {
        $url = Socialite::driver($driver)->stateless()->redirect()->getTargetUrl();
        return response()->json([
            "Success" => true,
            "url" => $url,
        ]);
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();

        if (!$user->token) {
            return response()->json(['Success' => false, "Message" => 'Failed'], 500);
        }

        $appUser = User::whereEmail($user->email)->first();

        if (!$appUser) {
            $appUser = User::create([
                'name' => $user->name,
                'password' => Hash::make(Str::random(7)),
                'email' => $user->email
            ]);

            $socialAccount = SocialAccount::create([
                'provider' => $provider,
                'provider_user_id' => $user->id,
                'user_id' => $appUser->id
            ]);
        } else {
            $socialAccount = $appUser->socialAccounts()->where('provider', $provider)->first();

            if (!$socialAccount) {
                $socialAccount = SocialAccount::create([
                    'provider' => $provider,
                    'provider_user_id' => $user->id,
                    'user_id' => $appUser->id
                ]);
            }
        }

        $passportToken = $appUser->createToken('Auth token')->accessToken;

        $userId = $appUser->id;
        $redis = new PredisClient();
        $redis->sadd('connected:users', $userId);
        broadcast(new LoggedIn($userId))->toOthers();

        return response()->json([
            "Success" => true,
            "access_token" => $passportToken
        ]);
    }
}
