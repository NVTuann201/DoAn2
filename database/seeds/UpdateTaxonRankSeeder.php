<?php

use App\DarwinCoreTaxon;
use Illuminate\Database\Seeder;

class UpdateTaxonRankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ranks = collect([
            "kingdom",
            "phylum",
            "class",
            "order",
            "family",
            "genus",
            "species",
        ]);

        DarwinCoreTaxon::whereNull('taxon_rank')->chunkById(100, function ($taxons) use ($ranks) {
            $taxons->each(function ($taxon) use ($ranks) {
                if (empty($taxon->taxon_rank)) {
                    $taxon_rank = null;
                    $ranksId = $ranks->map(function ($rank) use ($taxon) {
                        return ["rank" => $rank, "rank_id" => $taxon[$rank . "_id"], "is_has_id" => isset($taxon[$rank . "_id"])];
                    });
                    foreach ($ranksId->reverse() as $rankid) {
                        if (isset($taxon_rank)) {break;}
                        if ($rankid['is_has_id']) {
                            $taxon_rank = $rankid;
                        }
                    }
                    if (isset($taxon_rank)) {
                        $taxon->update(['taxon_rank' => $taxon_rank['rank']]);
                    }

                }
            });
        });

    }
}
