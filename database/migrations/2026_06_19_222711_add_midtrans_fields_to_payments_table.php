<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
            $table->string('snap_redirect_url')->nullable()->after('snap_token');
            $table->string('midtrans_transaction_id')->nullable()->after('snap_redirect_url');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'snap_redirect_url', 'midtrans_transaction_id']);
        });
    }
};
