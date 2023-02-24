<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoanThanhTrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doan_thanh_tras', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('loai_hinh_id');
            $table->foreign('loai_hinh_id')->references('id')->on('lookups')->onDelete('cascade');

            $table->string('so_quyet_dinh')->nullable();

            $table->date('thoi_gian_ky')->nullable();

            $table->unsignedInteger('co_quan_ky_id')->nullable();
            $table->foreign('co_quan_ky_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->unsignedInteger('che_do_id')->nullable();
            $table->foreign('che_do_id')->references('id')->on('lookups')->onDelete('cascade');

            $table->unsignedInteger('co_quan_thuc_hien_id')->nullable();
            $table->foreign('co_quan_thuc_hien_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->string('thoi_gian_tt')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doan_thanh_tras');
    }
}
