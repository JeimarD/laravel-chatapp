<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sent(Request $request)
    {
        $message = auth()->user()->messages()->create([
            'content' => $request->content,
            'chat_id' => $request->chat_id,
        ])->load('user:name');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['Success' => true, 'message' => $message]);
    }
}
