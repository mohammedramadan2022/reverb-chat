<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Display the chat interface.
     *
     * This method now redirects to the ChatRoomController as we've moved to a room-based approach.
     */
    public function index(Request $request)
    {
        // If a specific user_id is provided, find or create a room with that user
        if ($request->has('user_id')) {
            $userId = $request->query('user_id');
            $room = ChatRoom::findOrCreateRoom(auth()->id(), $userId);
            return redirect()->route('chat.room', $room->id);
        }

        // Otherwise, redirect to the rooms listing
        return redirect()->route('chat.rooms');
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'chat_room_id' => 'required|exists:chat_rooms,id',
        ]);

        // Verify that the user is part of this chat room
        $chatRoom = ChatRoom::findOrFail($validated['chat_room_id']);
        if (!$chatRoom->hasUser(auth()->id())) {
            return response()->json(['error' => 'You are not authorized to send messages to this chat room.'], 403);
        }

        $message = auth()->user()->messages()->create([
            'content' => $validated['content'],
            'chat_room_id' => $validated['chat_room_id'],
            // We can keep receiver_id for backward compatibility or remove it later
            'receiver_id' => $chatRoom->user_id_1 == auth()->id() ? $chatRoom->user_id_2 : $chatRoom->user_id_1,
        ]);

        return response()->json($message->load('user'));
    }

    /**
     * Get the latest messages.
     */
    public function getMessages(Request $request)
    {
        $lastId = $request->input('last_id', 0);
        $chatRoomId = $request->input('chat_room_id');

        if (!$chatRoomId) {
            return response()->json([]);
        }

        // Verify that the user is part of this chat room
        $chatRoom = ChatRoom::find($chatRoomId);
        if (!$chatRoom || !$chatRoom->hasUser(auth()->id())) {
            return response()->json(['error' => 'You are not authorized to view messages in this chat room.'], 403);
        }

        $messages = Message::with('user')
            ->where('chat_room_id', $chatRoomId)
            ->when($lastId > 0, function ($query) use ($lastId) {
                return $query->where('id', '>', $lastId);
            })
            ->latest()
            ->get();

        return response()->json($messages);
    }
}
