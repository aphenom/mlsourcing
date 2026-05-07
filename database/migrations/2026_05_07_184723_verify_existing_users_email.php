<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Auto-verify all users that were created programmatically (admin/agent invitations)
        // These users never went through self-registration email verification,
        // so their email_verified_at is null which blocks access to all routes.
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        //
    }
};
