<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhuDaDangSinhHocCaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khu_da_dang_sinh_hoc_caos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->text('mo_ta')->nullable();
            $table->float('dien_tich')->nullable();
            $table->string('quyet_dinh_thanh_lap')->nullable();
            $table->string('can_cu_de_xuat')->nullable();
            $table->date('ngay_thanh_lap')->nullable();
            $table->string('co_quan_ban_hanh')->nullable();
            $table->string('co_quan_quan_ly')->nullable();
            $table->boolean('quoc_te_cong_nhan')->default(true);
            $table->string('de_xuat_quan_ly')->nullable();
            $table->string('danh_hieu_quoc_te')->nullable();
            $table->string('muc_do')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->unsignedInteger('tinh_trang_id')->nullable();
            $table->foreign('tinh_trang_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->json('tinh_thanh_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('khu_da_dang_sinh_hoc_caos');
    }
}
