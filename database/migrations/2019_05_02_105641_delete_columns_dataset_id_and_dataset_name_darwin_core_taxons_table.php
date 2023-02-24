<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnsDatasetIdAndDatasetNameDarwinCoreTaxonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_taxons', function (Blueprint $table) {
            $table->dropColumn(['dataset_id', 'dataset_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('darwin_core_taxons', function (Blueprint $table) {
            $table->string('dataset_name', 5000)->nullable();
            $table->string('dataset_id')->nullable();
        });
    }
}
