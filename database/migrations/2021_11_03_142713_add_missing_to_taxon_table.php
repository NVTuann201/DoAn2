<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingToTaxonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nbds_taxon_extensions', function (Blueprint $table) {
            $table->integer('distribution_in_local')->nullable();
            $table->integer('base_origin')->nullable();
            $table->integer('cultivation_purpose')->nullable();
            $table->string('population')->nullable();
            $table->string('pressure')->nullable();
            $table->string('competing_species')->nullable();
            $table->string('symbiotic_species')->nullable();
            $table->string('protected')->nullable();
            $table->string('other_porotectec')->nullable();
            $table->string('protective_measures')->nullable();
            $table->string('benefit')->nullable();
            $table->string('other')->nullable();
        });
        Schema::table('darwin_core_occurrences', function (Blueprint $table) {
            $table->string('site_identification')->nullable();
            $table->double('diameter')->nullable()->default(0);
            $table->double('height')->nullable()->default(0);
            $table->string('health')->nullable();
            $table->string('health_diagnosis')->nullable();
            $table->string('sampling_method')->nullable();
            $table->string('specimen_preservation')->nullable();
            $table->string('water_temp')->nullable();
            $table->string('weather')->nullable();
            $table->string('ph_water')->nullable();
            $table->string('ph_soil')->nullable();
            $table->string('other')->nullable();
            $table->string('temperature')->nullable();
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
            $table->dropColumn([
                'distribution_in_local',
                'population',
                'base_origin',
                'cultivation_purpose',
                'pressure',
                'competing_species',
                'symbiotic_species',
                'protected',
                'other_porotectec',
                'protective_measures',
                'benefit',
                'other'
            ]);
        });
        Schema::table('darwin_core_occurrences', function (Blueprint $table) {
            $table->dropColumn([
                'site_identification',
                'diameter',
                'height',
                'health',
                'health_diagnosis',
                'sampling_method',
                'specimen_preservation',
                'water_temp', 'weather', 'ph_water', 'ph_soil', 'other',  'temperature'
            ]);
        });
    }
}
