<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetQuaThanhTraTinhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ket_qua_thanh_tra_tinhs', function (Blueprint $table) {
            $myfile = fopen("database/migrations/ketquathanhtra.json", "r");
            $files = json_decode(fread($myfile, filesize("database/migrations/ketquathanhtra.json")));
            $files = collect($files)->map(function ($item) {
                return $item->children;
            })->flatten();
            print(count($files));

            $table->increments('id');

            $table->unsignedInteger('doan_thanh_tra_id');
            $table->foreign('doan_thanh_tra_id')->references('id')->on('doan_thanh_tras')->onDelete('cascade');

            $table->string('so_van_ban')->nullable();

            $table->date('ngay_thong_bao')->nullable();

            $table->unsignedInteger('tinh_thanh_id')->nullable();
            $table->foreign('tinh_thanh_id')->references('id')->on('provinces')->onDelete('cascade');

            $table->string('thanh_phan')->nullable();
            $table->string('ban_hanh_vbpl')->nullable();
            $table->string('quan_ly_ddsh')->nullable();

            $table->json('files')->default('[]')->nullable();

            $files->each(function ($item) use ($table) {
                $type = $item->type;
                $field = $item->field;
                if ($type === 'text') {
                    $table->string($field)->nullable();
                } elseif ($type === 'file') {
                    $table->json($field)->default('[]')->nullable();

                } elseif ($type === 'date') {
                    $table->date($field)->nullable();
                } elseif ($type === 'boolean') {
                    $table->tinyInteger($field)->nullable();
                } elseif ($type === 'number') {
                    $table->double($field)->nullable();
                }
            });
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
        Schema::dropIfExists('ket_qua_thanh_tra_tinhs');
    }
}
