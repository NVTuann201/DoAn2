<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateKhuBaoTon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('protected_areas', function (Blueprint $table) {
            $table->json('quan_huyen')->nullable();
            $table->text('dia_chi')->nullable();
            $table->date('ngay_ban_hanh')->nullable();
            $table->text('co_quan_ban_hanh')->nullable();
            $table->json('files')->nullable();
            $table->text('co_quan_quan_ly')->nullable();
            $table->text('ke_hoach_quan_ly')->nullable();
            $table->string('status_year')->nullable()->change();
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
            $table->dropColumn('quan_huyen');
            $table->dropColumn('dia_chi');
            $table->dropColumn('ngay_ban_hanh');
            $table->dropColumn('co_quan_ban_hanh');
            $table->dropColumn('files');
            $table->dropColumn('co_quan_quan_ly');
            $table->dropColumn('ke_hoach_quan_ly');
        });
    }
}
