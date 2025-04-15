<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->enum('start_period', ['AM', 'PM'])->default('AM')->after('start_date');
            $table->enum('end_period', ['AM', 'PM'])->default('PM')->after('end_date');
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['start_period', 'end_period']);
        });
    }
};

