<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('chat_threads'); // Link to chat_threads table
            $table->foreignId('sender_id')->constrained('users'); // Link to users table (sender)
            $table->foreignId('recipient_id')->constrained('users'); // Link to users table (recipient)
            $table->text('message_content'); // Content of the message (text or file path)
            $table->string('file_path')->nullable(); // Path to file if attached
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
