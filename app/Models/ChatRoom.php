<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id_1',
        'user_id_2',
    ];

    /**
     * Get the first user associated with the chat room.
     */
    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_1');
    }

    /**
     * Get the second user associated with the chat room.
     */
    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_2');
    }

    /**
     * Get all messages in this chat room.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the other user in the room (not the current user).
     */
    public function getOtherUser($userId)
    {
        if ($this->user_id_1 == $userId) {
            return $this->user2;
        }

        return $this->user1;
    }

    /**
     * Check if a user is part of this chat room.
     */
    public function hasUser($userId): bool
    {
        return $this->user_id_1 == $userId || $this->user_id_2 == $userId;
    }

    /**
     * Find or create a chat room between two users.
     */
    public static function findOrCreateRoom($userId1, $userId2)
    {
        // Try to find an existing room
        $room = self::where(function ($query) use ($userId1, $userId2) {
            $query->where('user_id_1', $userId1)
                  ->where('user_id_2', $userId2);
        })->orWhere(function ($query) use ($userId1, $userId2) {
            $query->where('user_id_1', $userId2)
                  ->where('user_id_2', $userId1);
        })->first();

        // If no room exists, create one
        if (!$room) {
            $room = self::create([
                'user_id_1' => $userId1,
                'user_id_2' => $userId2,
                'name' => null, // Default to null, can be updated later
            ]);
        }

        return $room;
    }
}
