<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profesors', function (Blueprint $table) {
            if (! Schema::hasColumn('profesors', 'institution_id')) {
                $table->unsignedBigInteger('institution_id')->nullable()->after('id');
                // add index to speed queries
                $table->index('institution_id');
            }

            if (! Schema::hasColumn('profesors', 'activo')) {
                $table->boolean('activo')->default(true)->after('foto');
            }
        });

        // Try to add FK if possible
        if (Schema::hasTable('institutions') && Schema::hasColumn('profesors', 'institution_id')) {
            try {
                Schema::table('profesors', function (Blueprint $table) {
                    $table->foreign('institution_id')->references('id')->on('institutions')->cascadeOnDelete();
                });
            } catch (\Exception $e) {
                // swallow to avoid migrations failing in fragile environments; can be fixed manually if needed
            }
        }
    }

    public function down()
    {
        Schema::table('profesors', function (Blueprint $table) {
            if (Schema::hasColumn('profesors', 'institution_id')) {
                try { $table->dropForeign(['institution_id']); } catch (\Exception $e) {}
                $table->dropIndex(['institution_id']);
                $table->dropColumn('institution_id');
            }

            if (Schema::hasColumn('profesors', 'activo')) {
                $table->dropColumn('activo');
            }
        });
    }
};