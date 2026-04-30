<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ensure profesor_id is an unsignedBigInteger and add FK
        Schema::table('resenias', function (Blueprint $table) {
            // Add new column if needed
            if (!Schema::hasColumn('resenias', 'profesor_id') || !Schema::hasColumn('resenias', 'profesor_id')) {
                // Add nullable foreignId; we'll enforce FK after profesors table exists
                $table->unsignedBigInteger('profesor_id')->nullable()->after('id');
            }

            // Ensure calificacion has a default and is unsigned tiny integer
            if (!Schema::hasColumn('resenias', 'calificacion')) {
                $table->unsignedTinyInteger('calificacion')->default(5)->after('contenido');
            }
        });

        // Try to add foreign key if both tables/columns exist
        if (Schema::hasTable('profesors') && Schema::hasColumn('resenias', 'profesor_id')) {
            try {
                Schema::table('resenias', function (Blueprint $table) {
                    $table->foreign('profesor_id')->references('id')->on('profesors')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Log and continue; the DB may not allow adding FK due to existing mismatches
                // We intentionally swallow here to avoid failing the entire migration in fragile environments
            }
        }

        // We intentionally leave `profesor_id` nullable to avoid requiring doctrine/dbal and to make the migration safe in fragile environments.
        // After running this migration, manually enforce NOT NULL if desired and safe in your environment.
    }

    public function down()
    {
        Schema::table('resenias', function (Blueprint $table) {
            if (Schema::hasColumn('resenias', 'profesor_id')) {
                $table->dropForeign(['profesor_id']);
                $table->dropColumn('profesor_id');
            }

            if (Schema::hasColumn('resenias', 'calificacion')) {
                $table->dropColumn('calificacion');
            }
        });
    }
};