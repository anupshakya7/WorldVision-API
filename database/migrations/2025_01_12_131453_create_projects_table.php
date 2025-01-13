<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('region_id');
            $table->string('countrycode');
            $table->string('geocode');
            $table->decimal('latitude',10,6);
            $table->decimal('longitude',10,6);
            $table->string('project_title');
            $table->text('project_overview');
            $table->string('link');
            $table->integer('indicator_id');
            $table->integer('subindicator_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
