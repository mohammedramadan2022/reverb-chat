<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatRoomController extends Controller
{
    /**
     * Display a listing of the chat rooms for the current user.
     */
    public function index(): View
    {
        $user = auth()->user();
        $chatRooms = $user->chatRooms();

        // Eager load the other user in each room
        $chatRooms->load('user1', 'user2');

        return view('chat.rooms', compact('chatRooms'));
    }

    /**
     * Show the form for creating a new chat room.
     */
    public function create(): View
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('chat.create_room', compact('users'));
    }

    /**
     * Store a newly created chat room in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $room = ChatRoom::findOrCreateRoom(auth()->id(), $validated['user_id']);

        return redirect()->route('chat.room', $room->id);
    }

    /**
     * Display the specified chat room.
     */
    public function show(ChatRoom $room): View
    {
        // Check if the current user is part of this room
        if (!$room->hasUser(auth()->id())) {
            abort(403, 'You are not authorized to view this chat room.');
        }

        // Get the other user in the room
        $otherUser = $room->getOtherUser(auth()->id());

        // Get messages in this room
        $messages = $room->messages()->with('user')->latest()->get();

        // Get all rooms for the sidebar
        $chatRooms = auth()->user()->chatRooms();

        return view('chat.room', compact('room', 'messages', 'otherUser', 'chatRooms'));
    }
}
