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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sellerID')->constrained('users')->onDelete('cascade');
            $table->foreignId('requestID')->constrained('ordersrequests')->onDelete('cascade');
            $table->decimal('amount',10,2);
            $table->string('paymentMethod');
            $table->string('screenshot'); // URL or path to the screenshot
            $table->string('status');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


        Schema::dropIfExists('payments');
    }
};
