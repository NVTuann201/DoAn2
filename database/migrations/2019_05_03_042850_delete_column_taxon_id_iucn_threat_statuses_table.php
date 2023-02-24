<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnTaxonIdIucnThreatStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('iucn_threat_statuses', function (Blueprint $table) {
            $table->dropColumn(['taxon_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('iucn_threat_statuses', function (Blueprint $table) {
            $table->string('taxon_id', 255)->nullable();
        });
    }
}
