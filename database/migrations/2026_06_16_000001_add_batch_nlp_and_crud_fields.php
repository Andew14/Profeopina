<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('resenias', function (Blueprint $table) {
            if (!Schema::hasColumn('resenias', 'curso')) {
                $table->string('curso')->nullable()->after('contenido');
            }
            if (!Schema::hasColumn('resenias', 'procesada')) {
                $table->boolean('procesada')->default(false)->after('oculto');
            }
        });

        Schema::table('periods', function (Blueprint $table) {
            if (!Schema::hasColumn('periods', 'mid_time')) {
                $table->dateTime('mid_time')->nullable()->after('end_time');
            }
            if (!Schema::hasColumn('periods', 'mid_analysis_run')) {
                $table->boolean('mid_analysis_run')->default(false)->after('mid_time');
            }
            if (!Schema::hasColumn('periods', 'end_analysis_run')) {
                $table->boolean('end_analysis_run')->default(false)->after('mid_analysis_run');
            }
        });
    }

    public function down(): void {
        Schema::table('resenias', function (Blueprint $table) {
            $table->dropColumn(['curso', 'procesada']);
        });

        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn(['mid_time', 'mid_analysis_run', 'end_analysis_run']);
        });
    }
};
