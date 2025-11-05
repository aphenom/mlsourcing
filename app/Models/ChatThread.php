<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdersRequest;
use App\Models\ChatMessage;

class ChatThread extends Model
{
    use HasFactory;

    protected $fillable = ['order_request_id'];

    // Define the relationship with OrderRequest
    public function orderRequest()
    {
        return $this->belongsTo(OrdersRequest::class, 'order_request_id');  // Each thread is associated with one order request
    }
    
    // Define the relationship with ChatMessage
    public function messages()
    {
        return $this->hasMany(ChatMessage::class,'thread_id');  // Each thread has many messages
    }
    
}
