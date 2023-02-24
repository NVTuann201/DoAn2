<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnKingdomDarwinCoreTaxonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('darwin_core_taxons', function (Blueprint $table) {
            $table->dropColumn(['kingdom']);
            $table->foreign('kingdom_id')->references('id')->on('kingdoms'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('darwin_core_taxons', function (Blueprint $table) {
            $table->string('kingdom', 32)->nullable();
            $table->dropForeign('darwin_core_taxons_kingdom_id_foreign');       
        });
    }
}
