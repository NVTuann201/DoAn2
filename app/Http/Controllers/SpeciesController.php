<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DarwinCoreOccurrences;
use App\DarwinCoreTaxon;
use App\DatasetResource;
use App\Province;
use Session;
use Carbon\Carbon;
// use DB;
use App\DarwinCoreTaxonTree;
use Illuminate\Support\Facades\DB;

class SpeciesController extends Controller
{
    public function infoPercent(Request $request)
    {
        $darwin_core_taxon_tree_id = $request->get('darwin_core_taxon_tree_id');
        $info = DarwinCoreTaxonTree::query()->where('id', $darwin_core_taxon_tree_id)->get();
        $year = Carbon::now()->year;
        switch ($info[0]->rank) {
            case "kingdom":
                $datasetWithYear = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.kingdom_id', '=', 'darwin_core_taxon_tree.kingdom_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')->get();
                $data = [];
                for ($i = 2010; $i <= $year; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('year', $i)->count();
                    $data[] = $array;
                }
                $dataMonth = [];
                for ($i = 1; $i <= 12; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('month', $i)->count();
                    $dataMonth[] = $array;
                }
                $occurrences = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.kingdom_id', '=', 'darwin_core_taxon_tree.kingdom_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->groupBy('darwin_core_taxon_tree.kingdom_id', 'darwin_core_taxon_tree.name')
                    ->select('darwin_core_taxon_tree.kingdom_id', 'darwin_core_taxon_tree.name', DB::raw('count(darwin_core_occurrences.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesProvince = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.kingdom_id', '=', 'darwin_core_taxon_tree.kingdom_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('provinces', 'darwin_core_occurrences.province_id', '=', 'provinces.id')
                    ->groupBy('provinces.id')
                    ->select('provinces.id', 'provinces.name', DB::raw('count(provinces.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.kingdom_id', '=', 'darwin_core_taxon_tree.kingdom_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('dataset_resources', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $taxonDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.kingdom_id', '=', 'darwin_core_taxon_tree.kingdom_id')
                    ->join('dataset_resources', 'darwin_core_taxons.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                break;
            case "phylum":
                $datasetWithYear = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.phylum_id', '=', 'darwin_core_taxon_tree.phylum_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')->get();
                $data = [];
                for ($i = 2010; $i <= $year; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('year', $i)->count();
                    $data[] = $array;
                }
                $dataMonth = [];
                for ($i = 1; $i <= 12; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('month', $i)->count();
                    $dataMonth[] = $array;
                }
                $occurrences = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.phylum_id', '=', 'darwin_core_taxon_tree.phylum_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->groupBy('darwin_core_taxon_tree.phylum_id', 'darwin_core_taxon_tree.name')
                    ->select('darwin_core_taxon_tree.phylum_id', 'darwin_core_taxon_tree.name', DB::raw('count(darwin_core_occurrences.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesProvince = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.phylum_id', '=', 'darwin_core_taxon_tree.phylum_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('provinces', 'darwin_core_occurrences.province_id', '=', 'provinces.id')
                    ->groupBy('provinces.id')
                    ->select('provinces.id', 'provinces.name', DB::raw('count(provinces.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.phylum_id', '=', 'darwin_core_taxon_tree.phylum_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('dataset_resources', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $taxonDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.phylum_id', '=', 'darwin_core_taxon_tree.phylum_id')
                    ->join('dataset_resources', 'darwin_core_taxons.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                break;
            case "class":
                $datasetWithYear = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.class_id', '=', 'darwin_core_taxon_tree.class_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')->get();
                $data = [];
                for ($i = 2010; $i <= $year; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('year', $i)->count();
                    $data[] = $array;
                }
                $dataMonth = [];
                for ($i = 1; $i <= 12; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('month', $i)->count();
                    $dataMonth[] = $array;
                }
                $occurrences = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.class_id', '=', 'darwin_core_taxon_tree.class_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->groupBy('darwin_core_taxon_tree.class_id', 'darwin_core_taxon_tree.name')
                    ->select('darwin_core_taxon_tree.class_id', 'darwin_core_taxon_tree.name', DB::raw('count(darwin_core_occurrences.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesProvince = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.class_id', '=', 'darwin_core_taxon_tree.class_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('provinces', 'darwin_core_occurrences.province_id', '=', 'provinces.id')
                    ->groupBy('provinces.id')
                    ->select('provinces.id', 'provinces.name', DB::raw('count(provinces.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.class_id', '=', 'darwin_core_taxon_tree.class_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('dataset_resources', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $taxonDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.class_id', '=', 'darwin_core_taxon_tree.class_id')
                    ->join('dataset_resources', 'darwin_core_taxons.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                break;
            case "order":
                $datasetWithYear = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.order_id', '=', 'darwin_core_taxon_tree.order_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')->get();
                $data = [];
                for ($i = 2010; $i <= $year; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('year', $i)->count();
                    $data[] = $array;
                }
                $dataMonth = [];
                for ($i = 1; $i <= 12; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('month', $i)->count();
                    $dataMonth[] = $array;
                }
                $occurrences = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.order_id', '=', 'darwin_core_taxon_tree.order_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->groupBy('darwin_core_taxon_tree.order_id', 'darwin_core_taxon_tree.name')
                    ->select('darwin_core_taxon_tree.order_id', 'darwin_core_taxon_tree.name', DB::raw('count(darwin_core_occurrences.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesProvince = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.order_id', '=', 'darwin_core_taxon_tree.order_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('provinces', 'darwin_core_occurrences.province_id', '=', 'provinces.id')
                    ->groupBy('provinces.id')
                    ->select('provinces.id', 'provinces.name', DB::raw('count(provinces.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.order_id', '=', 'darwin_core_taxon_tree.order_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('dataset_resources', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $taxonDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.order_id', '=', 'darwin_core_taxon_tree.order_id')
                    ->join('dataset_resources', 'darwin_core_taxons.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                break;
            case "family":
                $datasetWithYear = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.family_id', '=', 'darwin_core_taxon_tree.family_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')->get();
                $data = [];
                for ($i = 2010; $i <= $year; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('year', $i)->count();
                    $data[] = $array;
                }
                $dataMonth = [];
                for ($i = 1; $i <= 12; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('month', $i)->count();
                    $dataMonth[] = $array;
                }
                $occurrences = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.family_id', '=', 'darwin_core_taxon_tree.family_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->groupBy('darwin_core_taxon_tree.family_id', 'darwin_core_taxon_tree.name')
                    ->select('darwin_core_taxon_tree.family_id', 'darwin_core_taxon_tree.name', DB::raw('count(darwin_core_occurrences.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesProvince = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.family_id', '=', 'darwin_core_taxon_tree.family_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('provinces', 'darwin_core_occurrences.province_id', '=', 'provinces.id')
                    ->groupBy('provinces.id')
                    ->select('provinces.id', 'provinces.name', DB::raw('count(provinces.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.family_id', '=', 'darwin_core_taxon_tree.family_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('dataset_resources', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $taxonDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.family_id', '=', 'darwin_core_taxon_tree.family_id')
                    ->join('dataset_resources', 'darwin_core_taxons.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                break;
            case "genus":
                $datasetWithYear = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.genus_id', '=', 'darwin_core_taxon_tree.genus_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')->get();
                $data = [];
                for ($i = 2010; $i <= $year; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('year', $i)->count();
                    $data[] = $array;
                }
                $dataMonth = [];
                for ($i = 1; $i <= 12; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('month', $i)->count();
                    $dataMonth[] = $array;
                }
                $occurrences = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.genus_id', '=', 'darwin_core_taxon_tree.genus_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->groupBy('darwin_core_taxon_tree.genus_id', 'darwin_core_taxon_tree.name')
                    ->select('darwin_core_taxon_tree.genus_id', 'darwin_core_taxon_tree.name', DB::raw('count(darwin_core_occurrences.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesProvince = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.genus_id', '=', 'darwin_core_taxon_tree.genus_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('provinces', 'darwin_core_occurrences.province_id', '=', 'provinces.id')
                    ->groupBy('provinces.id')
                    ->select('provinces.id', 'provinces.name', DB::raw('count(provinces.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.genus_id', '=', 'darwin_core_taxon_tree.genus_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('dataset_resources', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $taxonDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.genus_id', '=', 'darwin_core_taxon_tree.genus_id')
                    ->join('dataset_resources', 'darwin_core_taxons.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                break;
            case "species":
                $datasetWithYear = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.species_id', '=', 'darwin_core_taxon_tree.species_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')->get();
                $data = [];
                for ($i = 2010; $i <= $year; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('year', $i)->count();
                    $data[] = $array;
                }
                $dataMonth = [];
                for ($i = 1; $i <= 12; $i++) {
                    $array[0] = $i;
                    $array[1] = $datasetWithYear->where('month', $i)->count();
                    $dataMonth[] = $array;
                }
                $occurrences = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.species_id', '=', 'darwin_core_taxon_tree.species_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->groupBy('darwin_core_taxon_tree.species_id', 'darwin_core_taxon_tree.name')
                    ->select('darwin_core_taxon_tree.species_id', 'darwin_core_taxon_tree.name', DB::raw('count(darwin_core_occurrences.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesProvince = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.species_id', '=', 'darwin_core_taxon_tree.species_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('provinces', 'darwin_core_occurrences.province_id', '=', 'provinces.id')
                    ->groupBy('provinces.id')
                    ->select('provinces.id', 'provinces.name', DB::raw('count(provinces.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $occurrencesDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.species_id', '=', 'darwin_core_taxon_tree.species_id')
                    ->join('darwin_core_occurrences', 'darwin_core_taxons.id', '=', 'darwin_core_occurrences.darwin_core_taxon_id')
                    ->join('dataset_resources', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                $taxonDataset = DarwinCoreTaxonTree::query()->where('darwin_core_taxon_tree.id', $darwin_core_taxon_tree_id)
                    ->join('darwin_core_taxons', 'darwin_core_taxons.species_id', '=', 'darwin_core_taxon_tree.species_id')
                    ->join('dataset_resources', 'darwin_core_taxons.dataset_resource_id', '=', 'dataset_resources.id')
                    ->groupBy('dataset_resources.id')
                    ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(dataset_resources.id) as count'))
                    ->orderBy('count', 'DESC')->get();
                break;
            default:
        }

        return response()->json([
            'message' => __('message.success'),
            'datasetWithYear' => json_encode($data),
            'datasetWithMonth' => json_encode($dataMonth),
            'occurrences' => $occurrences,
            'occurrencesProvince' => $occurrencesProvince,
            'occurrencesDataset' => $occurrencesDataset,
            'taxonDataset' => $taxonDataset,
        ], 200, []);
    }
    public function getGbifMedia(Request $request)
    {
        $name = $request->get('name');
        $apiSpecies = 'https://api.gbif.org/v1/species/match?name=';
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiSpecies . urlencode($name),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $species = json_decode($response);
            if ($species->matchType == 'NONE') {
                return response(['results' => [], 'offset' => 0, 'limit' => 0, 'count' => 0], 200);
            } else {
                $key = null;
                $rank = null;
                switch ($species->rank) {
                    case 'KINGDOM':
                        $rank = 'kingdomKey';
                        $key = $species->kingdomKey;
                        break;
                    case 'PHYLUM':
                        $rank = 'phylumKey';
                        $key = $species->phylumKey;
                        break;
                    case 'CLASS':
                        $rank = 'classKey';
                        $key = $species->classKey;
                        break;
                    case 'ORDER':
                        $rank = 'orderKey';
                        $key = $species->orderKey;
                        break;
                    case 'FAMILY':
                        $rank = 'familyKey';
                        $key = $species->familyKey;
                        break;
                    case 'GENUS':
                        $rank = 'genusKey';
                        $key = $species->genusKey;
                        break;
                    case 'SPECIES':
                        $rank = 'speciesKey';
                        $key = $species->speciesKey;
                        break;
                    case 'SUBSPECIES':
                        $rank = 'speciesKey';
                        $key = $species->speciesKey;
                        break;
                    default:
                        $key = null;
                        $rank = null;
                }
                if ($key && $rank) {
                    $apiMedia = "https://api.gbif.org/v1/occurrence/search?.$rank=$key&media_type=StillImage";
                    $curl = curl_init();
                    $page = $request->get('page', 0);
                    $perPage = $request->get('per_page', 20);
                    curl_setopt_array($curl, array(
                        CURLOPT_URL =>  $apiMedia . "&offset=$page&limit=$perPage",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                    $data = json_decode($response);
                    return response()->json($data);
                }
                return response(['results' => [], 'offset' => 0, 'limit' => 0, 'count' => 0], 200);
            };
            return response()->json($species);
        } catch (\Exception $e) {
            return response(['results' => [], 'offset' => 0, 'limit' => 0, 'count' => 0, 'error' => $e], 500);
        }
    }
}
