<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationsipsDarwinCoreTaxonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_taxons', function (Blueprint $table) {           
            $table->foreign('phylum_id')->references('id')->on('phylums');            
            $table->foreign('class_id')->references('id')->on('classes');              
            $table->foreign('order_id')->references('id')->on('orders');              
            $table->foreign('family_id')->references('id')->on('families');              
            $table->foreign('genus_id')->references('id')->on('genus');              
            $table->foreign('species_id')->references('id')->on('species');              
            $table->foreign('sub_species_id')->references('id')->on('sub_species');              
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
            $table->dropForeign('darwin_core_taxons_phylum_id_foreign');         
            $table->dropForeign('darwin_core_taxons_class_id_foreign');              
            $table->dropForeign('darwin_core_taxons_order_id_foreign');              
            $table->dropForeign('darwin_core_taxons_family_id_foreign');              
            $table->dropForeign('darwin_core_taxons_genus_id_foreign');              
            $table->dropForeign('darwin_core_taxons_species_id_foreign');              
            $table->dropForeign('darwin_core_taxons_sub_species_id_foreign');              
        });
    }
}
