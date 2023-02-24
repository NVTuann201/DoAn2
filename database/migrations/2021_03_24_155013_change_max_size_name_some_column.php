<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMaxSizeNameSomeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS view_species");
        $list = ['kingdoms', 'phylums', 'classes', 'orders', 'families', 'genus', 'species', 'sub_species'];
        foreach ($list as $key => $value) {
            # code...
            Schema::table($value, function (Blueprint $table) {
                $table->string('name', 255)->change();
            });
        }
        $this->createView();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("DROP VIEW IF EXISTS view_species");
        $list = ['kingdoms', 'phylums', 'classes', 'orders', 'families', 'genus', 'species', 'sub_species'];
        foreach ($list as $key => $value) {
            # code...
            Schema::table($value, function (Blueprint $table) {
                $table->string('name', 100)->change();
            });
        }
        $this->createView();
    }

    private function createView()
    {
        DB::statement("
        CREATE VIEW view_species AS
                SELECT 'sub_species'::text AS rank,
                    '8'::text AS level_rank,
                    sub_species.id AS old_id,
                    sub_species.name,
                    sub_species.name_vietnamese
                FROM sub_species
                UNION
                SELECT 'species'::text AS rank,
                    '7'::text AS level_rank,
                    species.id AS old_id,
                    species.name,
                    species.name_vietnamese
                FROM species
                UNION
                SELECT 'genus'::text AS rank,
                    '6'::text AS level_rank,
                    genus.id AS old_id,
                    genus.name,
                    genus.name_vietnamese
                FROM genus
                UNION
                SELECT 'family'::text AS rank,
                    '5'::text AS level_rank,
                    families.id AS old_id,
                    families.name,
                    families.name_vietnamese
                FROM families
                UNION
                SELECT 'order'::text AS rank,
                    '4'::text AS level_rank,
                    orders.id AS old_id,
                    orders.name,
                    orders.name_vietnamese
                FROM orders
                UNION
                SELECT 'class'::text AS rank,
                    '3'::text AS level_rank,
                    classes.id AS old_id,
                    classes.name,
                    classes.name_vietnamese
                FROM classes
                UNION
                SELECT 'phylum'::text AS rank,
                    '2'::text AS level_rank,
                    phylums.id AS old_id,
                    phylums.name,
                    phylums.name_vietnamese
                FROM phylums
                UNION
                SELECT 'kingdom'::text AS rank,
                    '1'::text AS level_rank,
                    kingdoms.id AS old_id,
                    kingdoms.name,
                    kingdoms.name_vietnamese
                FROM kingdoms;
        ");
    }
}
