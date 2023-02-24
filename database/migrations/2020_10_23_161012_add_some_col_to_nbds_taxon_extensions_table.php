<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColToNbdsTaxonExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nbds_taxon_extensions', function (Blueprint $table) {
            $table->string('red_list')->nullable();
            $table->string('iucn_2012')->nullable();
            $table->string('provenance_in_local')->nullable();
            $table->string('natural')->nullable(); // cho biết loài sống tự nhiên hoang dã, hay thuần chủng ...
            $table->string('environmentally_sensitive')->nullable(); // Độ nhạy cảm với môi trường
            $table->string('rich_and_rare')->nullable();
            $table->text('note')->nullable();

            $table->text('other_vietnamese_law_to_protect_species')->change();
            $table->text('use')->change();
            $table->text('morphological_description')->change();
            $table->text('habitat')->change();
            $table->text('classification_of_behavioral_features')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nbds_taxon_extensions', function (Blueprint $table) {
            $table->dropColumn('red_list');
            $table->dropColumn('iucn_2012');
            $table->dropColumn('provenance_in_local');
            $table->dropColumn('natural'); 
            $table->dropColumn('environmentally_sensitive'); 
            $table->dropColumn('rich_and_rare');
            $table->dropColumn('note');

            // $table->string('other_vietnamese_law_to_protect_species')->change();
            // $table->string('use')->change();
            // $table->string('morphological_description')->change();
            // $table->string('habitat')->change();
            // $table->string('classification_of_behavioral_features')->change();
        });
    }
}
