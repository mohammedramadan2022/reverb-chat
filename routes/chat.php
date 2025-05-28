<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatRoomController;
use Illuminate\Support\Facades\Route;

// Chat room routes
Route::middleware('auth')->group(function () {
    // Legacy route - now redirects to room-based approach
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    // Chat rooms listing
    Route::get('/chat/rooms', [ChatRoomController::class, 'index'])->name('chat.rooms');

    // Create new chat room form
    Route::get('/chat/rooms/create', [ChatRoomController::class, 'create'])->name('chat.rooms.create');

    // Store new chat room
    Route::post('/chat/rooms', [ChatRoomController::class, 'store'])->name('chat.rooms.store');

    // Show specific chat room
    Route::get('/chat/rooms/{room}', [ChatRoomController::class, 'show'])->name('chat.room');

    // API endpoints for messages
    Route::post('/chat/messages', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
});
