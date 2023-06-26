<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Predis\Client as PredisClient;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return response()->json(["Success" => true, 'users' => $users], 200);
    }

    public function connected()
    {
        $redis = new PredisClient();
        $users = $redis->smembers('connected:users');
        return response()->json(["Success" => true, 'users' => $users], 200);
    }
}
