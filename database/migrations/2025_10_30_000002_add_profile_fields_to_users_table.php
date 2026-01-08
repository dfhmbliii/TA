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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('phone');
            $table->text('address')->nullable()->after('birth_date');
            $table->text('bio')->nullable()->after('address');
            $table->boolean('email_notifications')->default(true)->after('bio');
            $table->boolean('spk_updates')->default(true)->after('email_notifications');
            $table->boolean('system_announcements')->default(true)->after('spk_updates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'birth_date',
                'address',
                'bio',
                'email_notifications',
                'spk_updates',
                'system_announcements'
            ]);
        });
    }
};
