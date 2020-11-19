<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->date('initial_date')->nullable();
            $table->boolean('free')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('duration')->nullable();
            $table->string('insignia')->nullable();
            $table->decimal('porc_distintivo')->nullable();
            $table->string('title_insignia')->nullable();
            $table->string('certificado_finalizacion')->nullable();
            $table->string('plantilla_certificado')->nullable();
            $table->decimal('porc_aprobacion')->nullable();
            $table->string('certificado_finalizacion')->nullable();
            $table->string('requisito_previo')->nullable();
            $table->string('mas_previo')->nullable();
            $table->integer('retomas_curso')->nullable();
            $table->string('msj_end')->nullable();
            $table->bolean('seccion_gratuita')->nullable();
            $table->bolean('solicitud_curso')->nullable();
            $table->integer('units')->nullable();
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categoriesvideos');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('courses');
    }
}
