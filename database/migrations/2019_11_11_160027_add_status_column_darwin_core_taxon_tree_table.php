<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnDarwinCoreTaxonTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_taxon_tree', function (Blueprint $table) {
            $table->string('status')->nullable();
            $table->integer('synonym_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('darwin_core_taxon_tree', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('synonym_id');
        });
    }
}
