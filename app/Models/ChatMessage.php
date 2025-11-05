<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChatThread;
use App\Models\User;


class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['thread_id', 'sender_id', 'recipient_id', 'message_type', 'message_content'];

    // Define the relationship with ChatThread
    public function thread()
    {
        return $this->belongsTo(ChatThread::class, 'thread_id');  // Each message belongs to one thread, foreign key is 'thread_id'
    }

    // Define the relationship with the sender (User)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');  // The sender is a user (seller or agent)
    }

    // Define the relationship with the recipient (User)
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');  // The recipient is also a user (seller or agent)
    }

}
