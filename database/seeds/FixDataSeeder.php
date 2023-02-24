<?php

use App\KingDom;
use App\Species;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kingdoms')->update([
            'resource_id' => 1
        ]);
        DB::table('phylums')->update([
            'resource_id' => 1
        ]);
        DB::table('species')->update([
            'resource_id' => 1
        ]);
        Species::whereNotNull('synonym_id')->update([
            'status' => 'synonym'
        ]);
        echo 'Done';
 
    }
}
