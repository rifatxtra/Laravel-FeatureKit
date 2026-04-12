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
        Schema::table('traffic_logs', function (Blueprint $table) {
            // Session tracking: hash of IP+UA+date to group a visitor's journey
            $table->string('session_id', 64)->nullable()->index()->after('user_id');
            // Geographic data from IP lookup
            $table->string('country_code', 2)->nullable()->index()->after('referrer');
            $table->string('country_name', 100)->nullable()->after('country_code');
            // HTTP response info
            $table->smallInteger('status_code')->default(200)->index()->after('method');
            // Performance: request duration in milliseconds
            $table->float('response_time')->nullable()->after('status_code');
            // Visitor segmentation
            $table->boolean('is_new_visitor')->default(true)->after('is_bot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traffic_logs', function (Blueprint $table) {
            $table->dropColumn([
                'session_id',
                'country_code',
                'country_name',
                'status_code',
                'response_time',
                'is_new_visitor',
            ]);
        });
    }
};
