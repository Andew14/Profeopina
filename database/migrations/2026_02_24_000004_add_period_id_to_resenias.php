<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('resenias', function (Blueprint $table) {
            if (!Schema::hasColumn('resenias', 'period_id')) {
                $table->foreignId('period_id')->nullable()->constrained('periods')->onDelete('set null')->after('id');
            }
        });
    }

    public function down(): void {
        Schema::table('resenias', function (Blueprint $table) {
            if (Schema::hasColumn('resenias', 'period_id')) {
                $table->dropForeign(['period_id']);
                $table->dropColumn('period_id');
            }
        });
    }
};
