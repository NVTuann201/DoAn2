<?php

use App\DarwinCoreTaxon;
use App\PhyLum;
use App\Classes;
use App\Family;
use App\Genus;
use App\Order;
use App\Species;
use Illuminate\Database\Seeder;

class UpdateNewSevenTableClassify extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $listSpecies = DarwinCoreTaxon::where('taxon_rank', 'species')->whereNotNull('kingdom_id')->get()->pluck('id');
        $listClassify = ['phylum', 'class', 'order', 'family', 'genus'];
        $count = 0;
        foreach ($listSpecies as $id) {
            $taxon = DarwinCoreTaxon::findOrFail($id);
            foreach ($listClassify as $value) {
                if (empty($taxon[$value . '_id']) && isset($taxon[$value])) {
                    $count++;
                    switch ($value) {
                        case 'phylum':
                            $plylum = PhyLum::where('name', 'ilike', $taxon[$value]);
                            if ($plylum->count() == 0) {
                                $plylum = PhyLum::create([
                                    'name' => $taxon[$value],
                                    'kingdom_id' => $taxon->kingdom_id
                                ]);
                                $taxon[$value . '_id'] = $plylum->id;
                            } else {
                                $taxon[$value . '_id'] = $plylum->get()[0]['id'];
                            }
                            break;
                        case 'class':
                            if (isset($taxon[$listClassify[0] . '_id'])) {
                                $class = Classes::where('name', 'ilike', $taxon[$value]);
                                if ($class->count() == 0) {
                                    $class = Classes::create([
                                        'name' => $taxon[$value],
                                        'phylum_id' => $taxon->phylum_id
                                    ]);
                                    $taxon[$value . '_id'] = $class->id;
                                } else {
                                    $taxon[$value . '_id'] = $class->get()[0]['id'];
                                }
                            }
                            break;
                        case 'order':
                            if (isset($taxon[$listClassify[1] . '_id'])) {
                                $order = Order::where('name', 'ilike', $taxon[$value]);
                                if ($order->count() == 0) {
                                    $order = Order::create([
                                        'name' => $taxon[$value],
                                        'class_id' => $taxon->class_id
                                    ]);
                                    $taxon[$value . '_id'] = $order->id;
                                } else {
                                    $taxon[$value . '_id'] = $order->get()[0]['id'];
                                }
                            }
                            break;
                        case 'family':
                            if (isset($taxon[$listClassify[2] . '_id'])) {
                                $family = Family::where('name', 'ilike', $taxon[$value]);
                                if ($family->count() == 0) {
                                    $family = Family::create([
                                        'name' => $taxon[$value],
                                        'order_id' => $taxon->order_id
                                    ]);

                                    $taxon[$value . '_id'] = $family->id;
                                } else {
                                    $taxon[$value . '_id'] = $family->get()[0]->id;
                                }
                            }
                            break;
                        case 'genus':
                            if (isset($taxon[$listClassify[3] . '_id'])) {
                                $genus = Genus::where('name', 'ilike', $taxon[$value]);
                                if ($genus->count() == 0) {
                                    $genus = Genus::create([
                                        'name' => $taxon[$value],
                                        'family_id' => $taxon->family_id
                                    ]);
                                    $taxon[$value . '_id'] = $genus->id;
                                } else {
                                    $taxon[$value . '_id'] = $genus->get()[0]['id'];
                                }
                            }
                            break;
                        default:
                            break;
                    }
                    $taxon->save();
                }
            }
        }

        $query = DarwinCoreTaxon::where('taxon_rank', 'species')
            ->whereNotNull('specific_epithet')
            ->whereNull('species_id')
            ->whereNotNull('kingdom_id')
            ->whereNotNull('phylum_id')
            ->whereNotNull('class_id')
            ->whereNotNull('order_id')
            ->whereNotNull('family_id')
            ->whereNotNull('genus_id')->get();

        $listSpecies = $query->pluck('specific_epithet', 'id');
        $listGenus = $query->pluck('genus_id', 'id');

        foreach ($listSpecies as $key => $value) {
            $find = Species::where('name', 'ilike', "{$value}");
            if ($find->count() > 0) {
                $taxon = DarwinCoreTaxon::findOrFail($key);
                if (isset($taxon)) {
                    $taxon->species_id = $find->get()[0]['id'];
                    $taxon->save();
                }
            } else {
                $species = Species::create([
                    'name' => $value,
                    'genus_id' => $listGenus[$key]
                ]);
                $taxon = DarwinCoreTaxon::findOrFail($key);

                if (isset($taxon)) {
                    $taxon->species_id = $species->id;
                    $taxon->save();
                }
            }
        }
    }
}
