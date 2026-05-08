<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('importedproducts', function (Blueprint $table) {
            $table->decimal('purchase_price', 15, 2)->nullable()->after('totalPrice');
            $table->decimal('margin_percent', 7, 2)->default(0)->after('purchase_price');
            $table->decimal('client_unit_price', 15, 2)->nullable()->after('margin_percent');
            $table->decimal('client_total_price', 15, 2)->nullable()->after('client_unit_price');
        });

        Schema::table('ordersrequests', function (Blueprint $table) {
            $table->decimal('commission_percent', 5, 2)->default(0)->after('ShippingMethod');
            $table->decimal('commission_amount', 15, 2)->nullable()->after('commission_percent');
            $table->string('transit_mode', 20)->nullable()->after('commission_amount');
            $table->decimal('transit_client_amount', 15, 2)->nullable()->after('transit_mode');
            $table->decimal('transit_internal_margin', 15, 2)->nullable()->after('transit_client_amount');
            $table->string('transit_payment_mode', 20)->nullable()->after('transit_internal_margin');
        });
    }

    public function down(): void
    {
        Schema::table('importedproducts', function (Blueprint $table) {
            $table->dropColumn(['purchase_price', 'margin_percent', 'client_unit_price', 'client_total_price']);
        });

        Schema::table('ordersrequests', function (Blueprint $table) {
            $table->dropColumn(['commission_percent', 'commission_amount', 'transit_mode',
                                'transit_client_amount', 'transit_internal_margin', 'transit_payment_mode']);
        });
    }
};
