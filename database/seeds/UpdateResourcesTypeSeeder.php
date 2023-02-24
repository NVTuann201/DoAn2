<?php

use App\Classes;
use App\DarwinCoreTaxon;
use App\Family;
use App\Genus;
use App\KingDom;
use App\Order;
use App\PhyLum;
use App\Resource;
use App\Species;
use Illuminate\Database\Seeder;

class UpdateResourcesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resource = Resource::updateOrCreate(['name' => 'Khác']);
        Species::query()->update(["resource_id" => $resource->id]);
        Genus::query()->update(["resource_id" => null]);
        Family::query()->update(["resource_id" => null]);
        Order::query()->update(["resource_id" => null]);
        Classes::query()->update(["resource_id" => null]);
        PhyLum::query()->update(["resource_id" => null]);
        KingDom::query()->update(["resource_id" => null]);

        DarwinCoreTaxon::query()->where('taxon_rank', 'species')->update(["resource_id" => $resource->id]);

        Resource::updateOrCreate(['name' => 'Catalogue of Life']);
    }
}
