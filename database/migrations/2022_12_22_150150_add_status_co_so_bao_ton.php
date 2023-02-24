<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusCoSoBaoTon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('co_so_bao_tons', function (Blueprint $table) {
            $table->string('status')->default('Proposed');
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
        Schema::table('co_so_bao_tons', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
