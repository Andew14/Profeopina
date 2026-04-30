<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('profesors') && !Schema::hasColumn('profesors', 'foto')) {
            Schema::table('profesors', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('especialidad');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('profesors') && Schema::hasColumn('profesors', 'foto')) {
            Schema::table('profesors', function (Blueprint $table) {
                $table->dropColumn('foto');
            });
        }
    }
};