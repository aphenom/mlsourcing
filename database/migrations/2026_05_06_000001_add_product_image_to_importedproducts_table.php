<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('importedproducts', function (Blueprint $table) {
            $table->string('productURL')->nullable()->change();
            $table->string('productImage')->nullable()->after('productURL');
        });
    }

    public function down(): void
    {
        Schema::table('importedproducts', function (Blueprint $table) {
            $table->dropColumn('productImage');
            $table->string('productURL')->nullable(false)->change();
        });
    }
};
