<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnsOccurrenceIdTableDarwinCoreOccurrences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_occurrences', function (Blueprint $table) {
            $table->dropColumn(['occurrence_id']);
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
            $table->string('occurrence_id', 255)->nullable();            
        });
    }
}
