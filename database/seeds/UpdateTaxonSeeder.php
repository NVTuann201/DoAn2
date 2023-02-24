<?php

use App\DarwinCoreTaxon;
use App\Species;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer\SpContainer;

class UpdateTaxonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $query = DarwinCoreTaxon::query()->whereNull('species_id')->whereNotNull('specific_epithet')->where('taxon_rank', 'species')->get();
        $listId = $query->pluck('id');
        $listSpeciesID = $query->pluck('specific_epithet', 'id');
        // $print = true;
        foreach ($listSpeciesID as $key => $value) {
            $find = Species::where('name', 'ilike', "{$value}");
            // if ($print) {
                // error_log($find->get()[0]['id']);
            // error_log($value);
            // error_log($find->count());
            // }
            // $print = false;
            if ($find->count() > 0) {
                $taxon = DarwinCoreTaxon::findOrFail($key);
                if (isset($taxon)) {
                    $taxon->species_id = $find->get()[0]['id'];
                    $taxon->save();
                }
            }
        }
    }
}
