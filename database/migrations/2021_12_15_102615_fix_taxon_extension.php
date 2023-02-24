<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixTaxonExtension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('nbds_taxon_extensions', 'phu_luc_nghi_dinh', 'phan_hang_sach_do'))
        {
            Schema::table('nbds_taxon_extensions', function (Blueprint $table)
            {
                $table->text('phu_luc_nghi_dinh')->nullable();
                $table->text('phan_hang_sach_do')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('nbds_taxon_extensions', 'phu_luc_nghi_dinh', 'phan_hang_sach_do'))
        {
            Schema::table('nbds_taxon_extensions', function (Blueprint $table)
            {
                $table->dropColumn('phu_luc_nghi_dinh');
                $table->dropColumn('phan_hang_sach_do');
            });
        }
    }
}
