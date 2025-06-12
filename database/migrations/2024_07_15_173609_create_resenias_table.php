<?php

// database/migrations/2024_07_17_044441_create_resenias_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReseniasTable extends Migration
{
    public function up()
    {
        Schema::create('resenias', function (Blueprint $table) {
            $table->id();
            $table->integer('profesor_id');
            $table->text('contenido');
            $table->integer('calificacion');
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('resenias');
    }
}
