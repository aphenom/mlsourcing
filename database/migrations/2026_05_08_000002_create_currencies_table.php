<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();          // XOF, USD, RMB
            $table->string('symbol', 10);                  // FCFA, $, ¥
            $table->string('name', 60);
            $table->decimal('fcfa_per_unit', 15, 6);       // 1 unité = X FCFA (1 for XOF, 600 for USD, 83 for RMB)
            $table->boolean('is_active')->default(true);
            $table->timestamp('rate_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('currency_rate_history', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->decimal('fcfa_per_unit', 15, 6);
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->string('source', 30)->default('manual'); // manual | api
            $table->timestamp('changed_at')->useCurrent();

            $table->foreign('changed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency_rate_history');
        Schema::dropIfExists('currencies');
    }
};
