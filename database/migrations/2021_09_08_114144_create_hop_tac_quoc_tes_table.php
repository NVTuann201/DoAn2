<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHopTacQuocTesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hop_tac_quoc_tes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('ten')->nullable();
            $table->string('tinh_chat')->nullable();
            $table->date('ngay_ban_hanh')->nullable();
            $table->date('ngay_hieu_luc')->nullable();
            $table->date('ngay_het_han')->nullable();
            $table->text('doi_tac')->nullable();
            $table->string('co_quan_chu_tri')->nullable();
            $table->unsignedInteger('phan_cap_id')->nullable();
            $table->foreign('phan_cap_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->unsignedInteger('danh_nghia_id')->nullable();
            $table->foreign('danh_nghia_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('nguoi_ky')->nullable();
            $table->boolean('hieu_luc')->default(true);
            $table->unsignedInteger('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->json('files')->nullable();
            $table->text('noi_dung_chinh')->nullable();
            $table->text('noi_dung_toan_van')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->boolean('active')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hop_tac_quoc_tes');
    }
}
