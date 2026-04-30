<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('resenias', function (Blueprint $table) {
            if (! Schema::hasColumn('resenias', 'oculto')) {
                $table->boolean('oculto')->default(false)->after('calificacion');
            }
        });
    }

    public function down()
    {
        Schema::table('resenias', function (Blueprint $table) {
            if (Schema::hasColumn('resenias', 'oculto')) {
                $table->dropColumn('oculto');
            }
        });
    }
};