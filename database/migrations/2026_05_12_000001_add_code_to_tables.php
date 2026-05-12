<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('code', 12)->nullable()->unique()->after('id');
        });

        Schema::table('importedproducts', function (Blueprint $table) {
            $table->string('code', 12)->nullable()->unique()->after('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('code', 12)->nullable()->unique()->after('id');
        });

        $this->backfillCodes('payments',        'PAY', 'code');
        $this->backfillCodes('importedproducts', 'APR', 'code');
        $this->backfillCodes('users',            'USR', 'code');
        $this->backfillCodes('ordersrequests',   'CMD', 'requestNO');
    }

    public function down(): void
    {
        Schema::table('payments',        fn(Blueprint $t) => $t->dropColumn('code'));
        Schema::table('importedproducts',fn(Blueprint $t) => $t->dropColumn('code'));
        Schema::table('users',           fn(Blueprint $t) => $t->dropColumn('code'));
    }

    private function backfillCodes(string $table, string $prefix, string $column): void
    {
        $chars = 'ABCDEFGHJKMNPQRSTVWXYZ23456789';
        DB::table($table)->orderBy('id')->each(function ($row) use ($table, $prefix, $chars, $column) {
            do {
                $random = '';
                for ($i = 0; $i < 6; $i++) {
                    $random .= $chars[random_int(0, strlen($chars) - 1)];
                }
                $code = $prefix . '-' . $random;
            } while (DB::table($table)->where($column, $code)->exists());
            DB::table($table)->where('id', $row->id)->update([$column => $code]);
        });
    }
};
