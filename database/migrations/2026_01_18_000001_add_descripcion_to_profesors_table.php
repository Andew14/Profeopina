<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profesors', function (Blueprint $table) {
            if (!Schema::hasColumn('profesors', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('especialidad');
            }
        });
    }

    public function down()
    {
        Schema::table('profesors', function (Blueprint $table) {
            if (Schema::hasColumn('profesors', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
        });
    }
};
