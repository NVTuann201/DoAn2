<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDarwinCoreTaxonTree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('darwin_core_taxon_tree', function (Blueprint $table) {
            $table->increments('id');
            $table->text('rank')->nullable();
            $table->integer('species_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('name_vietnamese', 255)->nullable();
            $table->integer('genus_id')->nullable();
            $table->string('name_genus', 100)->nullable();
            $table->string('name_vietnamese_genus', 255)->nullable();
            $table->integer('family_id')->nullable();
            $table->string('name_family', 100)->nullable();
            $table->string('name_vietnamese_family', 255)->nullable();
            $table->integer('order_id')->nullable();
            $table->string('name_order', 100)->nullable();
            $table->string('name_vietnamese_order', 255)->nullable();
            $table->integer('class_id')->nullable();
            $table->string('name_class', 100)->nullable();
            $table->string('name_vietnamese_class', 255)->nullable();
            $table->integer('phylum_id')->nullable();
            $table->string('name_phylum', 100)->nullable();
            $table->string('name_vietnamese_phylum', 255)->nullable();
            $table->integer('kingdom_id')->nullable();
            $table->string('name_kingdom', 100)->nullable();
            $table->string('name_vietnamese_kingdom', 255)->nullable();
            $table->integer('number_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('darwin_core_taxon_tree');
    }
}