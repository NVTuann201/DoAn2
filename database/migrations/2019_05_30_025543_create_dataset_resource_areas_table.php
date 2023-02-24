<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatasetResourceAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dataset_resource_areas', function (Blueprint $table) {            
            $table->unsignedInteger('dataset_resource_id');
            $table->foreign('dataset_resource_id')->references('id')->on('dataset_resources')->onDelete('cascade');
            $table->unsignedInteger('protected_area_id');
            $table->foreign('protected_area_id')->references('id')->on('protected_areas')->onDelete('cascade');
            $table->unique(['dataset_resource_id', 'protected_area_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dataset_resource_areas');
    }
}
