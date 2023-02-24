<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TypeTextPublicSpecies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_taxon_tree', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('name_genus')->change();
            $table->text('name_family')->change();
            $table->text('name_order')->change();
            $table->text('name_class')->change();
            $table->text('name_phylum')->change();
            $table->text('name_kingdom')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
