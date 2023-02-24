<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetQuaQuanTracsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_quan_tracs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('thong_so_id')->nullable();
            $table->foreign('thong_so_id')->references('id')->on('thong_sos');
            $table->unsignedInteger('diem_quan_trac_id')->nullable();
            $table->foreign('diem_quan_trac_id')->references('id')->on('diem_quan_tracs');
            $table->float('ket_qua')->nullable();
            $table->string('don_vi_do')->nullable();
            $table->text('quy_chuan_viet_nam')->nullable();
            $table->text('ghi_chu')->nullable();
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
        Schema::dropIfExists('ket_qua_quan_tracs');
    }
}
