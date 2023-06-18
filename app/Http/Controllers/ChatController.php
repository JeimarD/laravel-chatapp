<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function create(Request $request)
    {
        // Get ID of authenticated user
        $userId = Auth::id();

        // Get ID of the user that has been clicked
        $friendUserId = $request->input('friend_user_id');

        // Get all existing chats of the authenticated user
        $existingChats = Chat::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        // Find the chat that includes both the authenticated user and the friend user
        $existingChat = null;
        foreach ($existingChats as $chat) {
            $users = $chat->users->pluck('id')->toArray();
            if (in_array($friendUserId, $users)) {
                $existingChat = $chat;
                break;
            }
        }

        if ($existingChat) {
            Log::info("Ya existe el chat $existingChat");
            return response()->json(['Success' => true, 'chatId' => $existingChat->id], 200);
        }

        $chat = Chat::create();
        $chat->users()->attach([$userId, $friendUserId]);
        Log::info("No existe el chat $chat");

        return response()->json(['Success' => true, 'chatId' => $chat->id], 200);
    }

    public function getMessages(Chat $chat)
    {
        $messages = $chat->messages()->with('user')->get();

        return response()->json(['Success' => true, 'messages' => $messages], 200);
    }
}
