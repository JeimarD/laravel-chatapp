<?php

namespace App\Http\Controllers\Auth;

use App\Events\LoggedIn;
use App\Events\LogOut;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Predis\Client as PredisClient;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $passwordGrantClient = Client::where('password_client', 1)->first();


        $data =  [
            'grant_type' => 'password',
            'client_id' => $passwordGrantClient->id,
            'client_secret' => $passwordGrantClient->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ];
        
        $tokenRequest = Request::create('/oauth/token', 'post', $data);
        $response = app()->handle($tokenRequest);

        if ($response->getStatusCode() == 200) {
            // Get the user ID based on the email
            $user = User::whereEmail($request->email)->first();
            $userId = $user->id ?? null;
    
            $redis = new PredisClient();
            $redis->sadd('connected:users', $userId);
            broadcast(new LoggedIn($userId))->toOthers();
        }

        return $response;
    }

    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if (!$user) {
            return response()->json(["success" => false, "message" => "Registration failed"], status: 500);
        }

        return response()->json(["success" => true, "message" => 'Registration succeded']);
    }

    public function logout(Request $request)
    {
        $userId = $request->userId;
        $redis = new PredisClient();
        $redis->srem('connected:users', $userId);
        broadcast(new LogOut($userId))->toOthers();
    }
}
