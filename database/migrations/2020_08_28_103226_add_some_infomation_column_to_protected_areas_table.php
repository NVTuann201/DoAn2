<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeInfomationColumnToProtectedAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('protected_areas', function (Blueprint $table) {
            //
            $table->string('name_vn')->nullable();
            $table->string('name_en')->nullable();
            $table->string('international_criteria')->nullable();
            $table->string('biodiversity_level')->nullable();//cấp độ đa dạng sinh học
            $table->string('management_type')->nullable();// phân cấp quản lý
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
            //
            $table->dropColumn('name_vn');
            $table->dropColumn('name_en');
            $table->dropColumn('international_criteria');
            $table->dropColumn('biodiversity_level');
            $table->dropColumn('management_type');
        });
    }
}
