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
        Schema::create('agent_destinations', function (Blueprint $table) {
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('destination_country_id')->constrained('destination_countries')->onDelete('cascade');
            $table->primary(['agent_id', 'destination_country_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_destinations');
    }
};
