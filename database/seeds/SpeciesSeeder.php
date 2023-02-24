<?php

use App\Genus;
use App\Species;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/species.json", "r");
        $files = json_decode(fread($myfile, filesize("database/seeds/species.json")));
        $data = $files->data;
        $accepcted = array_filter($data, function($item){
            return $item->taxonomicStatus == 'accepted name';
        });
        $synomys = array_filter($data, function($item){
            return $item->taxonomicStatus == 'synonym';
        });
        // DB::beginTransaction();
        // $tongAcc = count($accepcted);
        // $i = 0;
        // foreach ($accepcted as $item) {
        //     $refe = Genus::where('name', $item->genus)->first();
        //     if ($refe && $item->scientificName) {
        //         DB::table('species')->insert([
        //             'name' => $item->scientificName,
        //             'genus_id' => $refe->id,
        //         ]);
        //     }
        //     $i++;
        //     echo "Created Accepted: ".$i."/".$tongAcc."\n";
        // }
        // DB::commit();
        // echo 'Done Accepted';
        $tongSy = count($synomys);
        $j = 0;
        DB::beginTransaction();
        foreach ($synomys as $item) {
            $refe = Genus::where('name', $item->genus)->first();
            $syno = array_filter($accepcted, function($acc) use ($item){
                return $acc->taxonID == $item->acceptedNameUsageID;
            });
            $syno = $syno ?  array_slice($syno, 0, 1)[0] : null;
            $syno_id = $syno  && Species::where('name', $syno->scientificName)->first() ? Species::where('name', $syno->scientificName)->first()->id : null;
            if ($refe && $item->scientificName && $syno_id) {
                DB::table('species')->insert([
                    'name' => $item->scientificName,
                    'genus_id' => $refe->id,
                    'synonym_id' => $syno_id
                ]);
                $j++;
                echo "Created synonym: ".$j."/".$tongSy."\n";
            }
        }
        DB::commit();
        echo "Created: ".$j."/".$tongSy;
    }
}
