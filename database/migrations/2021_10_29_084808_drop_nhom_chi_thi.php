<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropNhomChiThi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_this', function (Blueprint $table) {
            $table->dropColumn('nhom_chi_thi_id');
        });
        Schema::dropIfExists('nhom_chi_this');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('nhom_chi_this', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('ten');
            $table->text('mo_ta')->nullable();
        });
        Schema::create('chi_this', function (Blueprint $table) {
            $table->unsignedInteger('nhom_chi_thi_id')->nullable();
            $table->foreign('nhom_chi_thi_id')->references('id')->on('nhom_chi_this')->onDelete('cascade');
        });
    }
}
