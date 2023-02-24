<?php

use App\DarwinCoreTaxon;
use App\DatasetResource;
use Illuminate\Database\Seeder;

class changeSomeDatasetDraftToPublic extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $listDataset = DarwinCoreTaxon::whereNotNull('species_id')
                                        ->where('taxon_rank', 'species')
                                        ->whereNotNull('dataset_resource_id')
                                        ->get()->pluck('dataset_resource_id');
        $log = [];
        foreach($listDataset as $id){
            $dataset = DatasetResource::findOrFail($id);
            if(isset($dataset) && $dataset->status == 'draft'){
                $dataset->status = 'public';
                $log[] = [
                    $id => $dataset->title
                ];
                $dataset->save();
            }
        }
    }
}
