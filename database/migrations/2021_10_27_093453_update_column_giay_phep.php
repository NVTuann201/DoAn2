<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnGiayPhep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('giay_phep_da_dang_sinh_hocs', function (Blueprint $table) {
            $table->dropForeign(['ten_giay_phep_id']);
            $table->dropColumn('ten_giay_phep_id');
            $table->renameColumn('loai_giay_phep_id', 'loai_cap_phep_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('giay_phep_da_dang_sinh_hocs', function (Blueprint $table) {
            $table->renameColumn('loai_cap_phep_id', 'loai_giay_phep_id');
            $table->unsignedInteger('ten_giay_phep_id')->nullable();
            $table->foreign('ten_giay_phep_id')->references('id')->on('lookups')->onDelete('cascade');
        });
    }
}
