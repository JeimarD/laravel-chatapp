<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('messages')->get();
        return response()->json(["Success" => true, 'users' => $users], 200);
    }
}