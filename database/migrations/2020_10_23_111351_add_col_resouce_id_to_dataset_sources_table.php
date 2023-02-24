<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColResouceIdToDatasetSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dataset_resources', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
        Schema::table('sub_species', function (Blueprint $table) {
            $table->unsignedInteger('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dataset_resources', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });
        Schema::table('sub_species', function (Blueprint $table) {
            $table->dropColumn('resource_id');
        });
    }
}
