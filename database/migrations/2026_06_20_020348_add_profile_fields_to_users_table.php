<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('place_of_birth')->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('place_of_birth');
            $table->string('phone', 20)->nullable()->after('date_of_birth');
            $table->text('address')->nullable()->after('phone');
            $table->string('id_card_number', 30)->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['place_of_birth', 'date_of_birth', 'phone', 'address', 'id_card_number']);
        });
    }
};
