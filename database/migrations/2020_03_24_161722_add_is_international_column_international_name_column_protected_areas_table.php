<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsInternationalColumnInternationalNameColumnProtectedAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('protected_areas', function (Blueprint $table) {
            $table->boolean('isInternational')->default(false);
            $table->string('international_name')->nullable();
            $table->string('metadataid')->change()->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('protected_areas', function (Blueprint $table) {
            $table->dropColumn('isInternational');
            $table->dropColumn('international_name');
            $table->string('metadataid')->change()->nullable();
        });
    }
}
