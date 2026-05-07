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
        Schema::table('importedproducts', function (Blueprint $table) {
            $table->string('measurement_type')->nullable()->default('weight')->after('weight');
            $table->decimal('cbm', 10, 4)->nullable()->after('measurement_type');
        });
    }

    public function down(): void
    {
        Schema::table('importedproducts', function (Blueprint $table) {
            $table->dropColumn(['measurement_type', 'cbm']);
        });
    }
};
