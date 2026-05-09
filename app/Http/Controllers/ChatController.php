<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatThread;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\NotificationService;


class ChatController extends Controller
{
    public function sendMessage(Request $request, $orderRequestId)
    {
        // Validate the request: message_content is mandatory, file is optional but should be an image or video if present
        $validated = $request->validate([
            'message_content' => 'required|string',  // Make message_content mandatory
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mkv|max:10240',  // Validate file type (images and videos)
            'recipient_id' => 'required|integer|exists:users,id',  // Ensure recipient exists
            'sender_id' => 'required|integer|exists:users,id',
        ]);
    
        // Find or create the chat thread for the order request
        $chatThread = ChatThread::firstOrCreate([
            'order_request_id' => $orderRequestId,
        ]);
    
        // Handle file upload if a file is provided
        $filePath = null;
        
        
        $message = new ChatMessage();
        $message->thread_id = $chatThread->id;
        $message->recipient_id = $validated['recipient_id'];
        $message->sender_id = $validated['sender_id'];
        $message->message_content = $validated['message_content'];
    
        if ($request->hasFile('file')) {    
            // Generate unique file name to avoid conflicts
            $fileName = time() . '-' . $request->file('file')->getClientOriginalName();
            $folderName = 'chatter/' . $chatThread->id; // Use chat thread ID for folder
    
            // Store file directly in public/storage
            $filePath = $request->file('file')->storeAs($folderName, $fileName, 'public'); 
            $message->file_path = $filePath;
        }
        
        // Save the message to the database
        $message->save();

        // Notify the recipient (db + mail + sms)
        $recipient = User::find($validated['recipient_id']);
        if ($recipient) {
            $link = '#';
            if ($recipient->isAgent()) {
                $link = route('agent.followUpProductRequest',  ['id' => $orderRequestId]);
            } elseif ($recipient->isSeller()) {
                $link = route('seller.followUpProductRequest', ['id' => $orderRequestId]);
            } elseif ($recipient->isAdmin()) {
                $link = route('admin.followUpProductRequest',  ['id' => $orderRequestId]);
            }
            NotificationService::notify(
                $recipient,
                (int) $orderRequestId,
                'new_chat_message',
                ['sender_name' => auth()->user()->name],
                $link,
                ['db', 'mail', 'sms']
            );
        }

        // Return a JSON response to be processed by the front-end
        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_name' => auth()->user()->name,
                'recipient_id' => $message->recipient_id,
                'message' => $message->message_content,
                'file_path' => $filePath ? asset('storage/' . $filePath) : null,
                'created_at' => $message->created_at->isoFormat('LLL'), // Now `created_at` will be available because we saved the message
            ],
        ]);
    }    
}
