<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCoSobaoTon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_so_bao_tons', function (Blueprint $table) {
            $table->text('co_quan_quan_ly')->nullable();
            $table->text('hinh_thuc_bao_ton')->nullable();
            $table->json('files')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_so_bao_tons', function (Blueprint $table) {
            $table->dropColumn('co_quan_quan_ly');
            $table->dropColumn('hinh_thuc_bao_ton');
            $table->dropColumn('files');
        });
    }
}
