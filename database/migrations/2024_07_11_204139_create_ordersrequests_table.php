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
        Schema::create('ordersrequests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sellerID')->constrained('users')->onDelete('cascade');
            $table->foreignId('agentID')->constrained('users')->onDelete('cascade');
            $table->string('requestNO')->unique();
            $table->string('statusRequest');
            $table->string('countryFrom');
            $table->string('countryTo');
            $table->string('ShippingMethod');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordersrequests');
    }
};
