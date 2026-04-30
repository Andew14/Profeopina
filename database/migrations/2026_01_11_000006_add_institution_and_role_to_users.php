<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'institution_id')) {
                $table->unsignedBigInteger('institution_id')->nullable()->after('id');
                $table->index('institution_id');
            }

            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('institution_id');
            }
        });

        if (Schema::hasTable('institutions') && Schema::hasColumn('users', 'institution_id')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('institution_id')->references('id')->on('institutions')->cascadeOnDelete();
                });
            } catch (\Exception $e) {
                // ignore FK create errors
            }
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'institution_id')) {
                try { $table->dropForeign(['institution_id']); } catch (\Exception $e) {}
                $table->dropIndex(['institution_id']);
                $table->dropColumn('institution_id');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};