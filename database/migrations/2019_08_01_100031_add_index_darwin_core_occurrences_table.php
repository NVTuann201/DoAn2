<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexDarwinCoreOccurrencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_occurrences', function (Blueprint $table) {
            $table->index('scientific_name')->change();
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
            $table->dropIndex(['scientific_name']);
        });
    }
}
