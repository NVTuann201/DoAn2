<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatNgapNuocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dat_ngap_nuocs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('ten')->nullable();
            $table->text('mo_ta')->nullable();
            $table->float('dien_tich')->nullable();
            $table->unsignedInteger('tinh_trang_id')->nullable();
            $table->foreign('tinh_trang_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('van_ban_dieu_chinh')->nullable();
            $table->text('quyet_dinh_thanh_lap')->nullable();
            $table->date('ngay_thanh_lap')->nullable();
            $table->text('co_quan_ban_hanh')->nullable();
            $table->text('co_quan_quan_ly')->nullable();
            $table->boolean('quoc_te_cong_nhan')->default(true);
            $table->text('danh_hieu_quoc_te')->nullable();   
            $table->unsignedInteger('khu_bao_ton_id')->nullable();
            $table->foreign('khu_bao_ton_id')->references('id')->on('protected_areas');
            $table->unsignedInteger('phan_cap_id')->nullable();
            $table->foreign('phan_cap_id')->references('id')->on('lookups')->onDelete('cascade');      
            $table->integer('nam_cong_nhan')->nullable();   
            $table->text('hinh_thuc_bao_ton')->nullable();   
            $table->text('quy_hoach')->nullable();   
            $table->text('doi_tuong_bao_ve')->nullable();   
            $table->text('khai_thac_su_dung')->nullable();   
            $table->text('tieu_chi_dap_ung')->nullable();  
            $table->unsignedInteger('muc_do_quan_trong_id')->nullable();
            $table->foreign('muc_do_quan_trong_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->text('ghi_chu')->nullable();
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
        Schema::dropIfExists('dat_ngap_nuocs');
    }
}
