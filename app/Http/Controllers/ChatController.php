<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Display the chat interface.
     */
    public function index(): View
    {
        $messages = Message::with('user')->latest()->get();

        return view('chat.index', compact('messages'));
    }

    /**
     * Store a new message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = auth()->user()->messages()->create([
            'content' => $validated['content'],
        ]);

        return response()->json($message->load('user'));
    }

    /**
     * Get the latest messages.
     */
    public function getMessages(Request $request)
    {
        $lastId = $request->input('last_id', 0);

        $messages = Message::with('user')
            ->when($lastId > 0, function ($query) use ($lastId) {
                return $query->where('id', '>', $lastId);
            })
            ->latest()
            ->get();

        return response()->json($messages);
    }
}
