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
        Schema::create('importedproducts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requestID')->constrained('ordersrequests')->onDelete('cascade');
            $table->string('productName');
            $table->integer('qte');
            $table->string('productCategory')->nullable();
            $table->decimal('unitPrice',10,2);
            $table->decimal('totalPrice',10,2);
            $table->string('weight')->nullable();
            $table->string('productURL');
            $table->string('trackingNumber')->nullable();
            $table->string('carrier')->nullable();
            $table->string('productSpecification')->nullable();
            $table->string('agentNote')->nullable();
            $table->string('statusProduct')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('importedproducts');
    }
};
