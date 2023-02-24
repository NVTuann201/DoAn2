<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnsStateProvinceTableDarwinCoreOccurrences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_occurrences', function (Blueprint $table) {
            $table->dropColumn(['state_province']);
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
            $table->string('state_province', 255)->nullable();            
        });
    }
}
