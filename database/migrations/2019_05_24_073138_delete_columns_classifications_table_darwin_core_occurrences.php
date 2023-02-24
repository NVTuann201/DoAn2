<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnsClassificationsTableDarwinCoreOccurrences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_occurrences', function (Blueprint $table) {
            $table->dropColumn(['kingdom', 'phylum', 'class', 'order', 'family', 'genus', 'subgenus', 'taxon_rank']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('darwin_core_occurrences', function (Blueprint $table) {
            $table->string('kingdom', 255)->nullable();            
            $table->string('phulum', 255)->nullable();            
            $table->string('class', 255)->nullable();            
            $table->string('order', 255)->nullable();            
            $table->string('family', 255)->nullable();            
            $table->string('genus', 255)->nullable();            
            $table->string('subgenus', 255)->nullable();            
            $table->string('taxon_rank', 255)->nullable();            
        });
    }
}
