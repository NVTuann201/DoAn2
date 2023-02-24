<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhanBoHaNoi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nbds_taxon_extensions', function (Blueprint $table) {
            $table->text('phan_bo_ha_noi')->nullable();
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
        Schema::table('nbds_taxon_extensions', function (Blueprint $table) {
            $table->dropColumn('phan_bo_ha_noi');
        });
    }
}
