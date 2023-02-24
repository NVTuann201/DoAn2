<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationshipDarwinCoreSimpleImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_simple_images', function (Blueprint $table) {
            $table->foreign('darwin_core_taxon_id')->references('id')->on('darwin_core_taxons');
            $table->foreign('darwin_core_occurrence_id')->references('id')->on('darwin_core_occurrences');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('darwin_core_simple_images', function (Blueprint $table) {
            $table->dropForeign('darwin_core_simple_images_darwin_core_taxon_id_foreign');
            $table->dropForeign('darwin_core_simple_images_darwin_core_occurrence_id_foreign');
        });
    }
}
