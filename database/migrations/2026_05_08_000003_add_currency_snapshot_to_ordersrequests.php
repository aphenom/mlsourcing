<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordersrequests', function (Blueprint $table) {
            $table->string('currency_code', 10)->default('XOF')->after('transit_payment_mode');
            $table->decimal('currency_rate', 15, 6)->default(1)->after('currency_code'); // fcfa_per_unit at quote time
        });
    }

    public function down(): void
    {
        Schema::table('ordersrequests', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'currency_rate']);
        });
    }
};
