<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetQuaThanhTraCoSosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('ket_qua_thanh_tra_co_sos', function (Blueprint $table) {
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

            $table->date('thoi_gian_ky')->nullable();

            $table->unsignedInteger('co_quan_ky_id')->nullable();
            $table->foreign('co_quan_ky_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->unsignedInteger('co_so_id')->nullable();
            $table->string('co_so_type')->nullable();

            $table->string('ten_don_vi_quan_ly')->nullable();

            $table->string('dia_chi_don_vi_quan_ly')->nullable();

            $table->string('noi_dung_tt')->nullable();

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
        Schema::dropIfExists('ket_qua_thanh_tra_co_sos');
    }
}
