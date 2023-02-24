<?php

namespace App\Http\Controllers;

use App\DarwinCoreOccurrences;
use App\DarwinCoreSimpleImage;
use App\DarwinCoreTaxon;
use App\DarwinCoreTaxonTree;
use App\DatasetResource;
use App\ProtectedArea;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Session;

class DetailController extends Controller
{
    use QueryTrait;
    public function detailSpecies(Request $request, $id)
    {
        $species = DarwinCoreTaxonTree::query()->where('id', $id)->first();
        $species_id = $species['species_id'];
        $images = 0;
        $rank = $species->rank;
        $listID = $this->getListIdTaxon($rank, $species);
        // $query = DarwinCoreTaxon::query()->whereHas('dataResource', function ($query) {
        //     $query->filterStatusPublic();
        // })->with('dataResource')->whereIn('id', $listID);
        // $taxon=$query->get();

        // $datasets=DatasetResource::whereHas('darwinCoreTaxons',function($query)use($listID){
        //     $query->whereIn('id', $listID);
        // })->paginate(10);

        // $datasets = $taxon->pluck('dataResource');
        $images = DarwinCoreSimpleImage::with(['darwinCoreTaxon'])->select(['id', 'identifier', 'darwin_core_taxon_id'])->whereIn('darwin_core_taxon_id', $listID)->count();

        $synonyms = DarwinCoreTaxonTree::where('synonym_id', $species->{$rank . '_id'})->where('rank', $rank)->get();
        if (count($synonyms) == 0) {
            $synonyms = null;
        }
        if (empty($species->synonym_id)) {
            $accepted = null;
        } else {
            $accepted = DarwinCoreTaxonTree::where('rank', $rank)->where($rank . '_id', $species->synonym_id)->where('status', 'accepted')->get();
        }

        $kingdoms = DarwinCoreTaxonTree::query()->where('rank', 'kingdom')->get();
        $kingdom = null;
        $class = null;
        $phylum = null;
        $order = null;
        $family = null;
        $genus = null;
        $children = null;
        $speciesAside = null;
        if (isset($species['kingdom_id'])) {
            $kingdom_id = $species['kingdom_id'];
            $kingdom = DarwinCoreTaxonTree::query()->where('kingdom_id', $kingdom_id)->where('rank', 'kingdom')->get();
        }
        if (isset($species['phylum_id'])) {
            $phylum_id = $species['phylum_id'];
            $phylum = DarwinCoreTaxonTree::query()->where('phylum_id', $phylum_id)->where('rank', 'phylum')->get();
        }
        if (isset($species['class_id'])) {
            $class_id = $species['class_id'];
            $class = DarwinCoreTaxonTree::query()->where('class_id', $class_id)->where('rank', 'class')->get();
        }

        if (isset($species['order_id'])) {
            $order_id = $species['order_id'];
            $order = DarwinCoreTaxonTree::query()->where('order_id', $order_id)->where('rank', 'order')->get();
        }
        if (isset($species['family_id'])) {
            $family_id = $species['family_id'];
            $family = DarwinCoreTaxonTree::query()->where('family_id', $family_id)->where('rank', 'family')->get();
        }
        if (isset($species['genus_id'])) {
            $genus_id = $species['genus_id'];
            $genus = DarwinCoreTaxonTree::query()->where('genus_id', $genus_id)->where('rank', 'genus')->get();
        }
        if (isset($species['species_id'])) {
            $species_id = $species['species_id'];
            $speciesAside = DarwinCoreTaxonTree::query()->where('species_id', $species_id)->where('rank', 'species')->get();
        }
        if ($rank == 'kingdom') {
            $children = DarwinCoreTaxonTree::query()
                ->whereIn('rank', ['phylum', 'class'])
                ->where('kingdom_id', $species['kingdom_id'])->get();
        }
        if ($rank == 'phylum') {
            $children = DarwinCoreTaxonTree::query()
                ->whereIn('rank', ['class', 'order'])
                ->where('phylum_id', $species['phylum_id'])->get();
        }
        if ($rank == 'class') {
            $children = DarwinCoreTaxonTree::query()
                ->whereIn('rank', ['order', 'family'])
                ->where('class_id', $species['class_id'])->get();
        }
        if ($rank == 'order') {
            $children = DarwinCoreTaxonTree::query()
                ->whereIn('rank', ['family', 'genus'])
                ->where('order_id', $species['order_id'])->get();
        }
        if ($rank == 'family') {
            $children = DarwinCoreTaxonTree::query()
                ->whereIn('rank', ['genus', 'species'])
                ->where('family_id', $species['family_id'])->get();
        }
        if ($rank == 'genus') {
            $children = DarwinCoreTaxonTree::query()
                ->whereIn('rank', ['species'])
                ->where('genus_id', $species['genus_id'])->get();
        }

        return response()->json([
            'message' => 'Thành công',
            'result' => $species,
            'kingdoms' => $kingdoms,
            'kingdom' => $kingdom,
            'phylum' => $phylum,
            'class' => $class,
            'order' => $order,
            'family' => $family,
            'genus' => $genus,
            'species' => $speciesAside,
            'children' => $children,
            'synonyms' => $synonyms,
            'accepted' => $accepted,
            'images' => $images,
        ], 200, []);
    }

    public function getDataset(Request $request, $id)
    {
        $species = DarwinCoreTaxonTree::query()->where('id', $id)->first();
        $rank = $species->rank;
        $listID = $this->getListIdTaxon($rank, $species);
        $page = $request->get('page', 1);
        $datasets = DatasetResource::whereHas('darwinCoreTaxons', function ($query) use ($listID) {
            $query->whereIn('id', $listID);
        })->paginate(10, ['*'], 'page', $page);
        return response()->json([
            'result' => $datasets,
        ], 200, []);
    }
    public function getTaxon(Request $request, $id)
    {
        $species = DarwinCoreTaxonTree::query()->where('id', $id)->first();
        $rank = $species->rank;
        $listID = $this->getListIdTaxon($rank, $species);
        $page = $request->get('page', 1);
        $query = DarwinCoreTaxon::query()->with(['kingDom:name,id,name_vietnamese',
            'phylum:name,id,name_vietnamese',
            'class:name,id,name_vietnamese',
            'order:name,id,name_vietnamese',
            'family:name,id,name_vietnamese',
            'genus:name,id,name_vietnamese',
            'species:name,id,name_vietnamese',
            'dataResource:id,title,intellectual_rights,citation,geographic_description,organization_id,original_filename',
            'dataResource.organization:id,name', 'nbdsTaxonExtension'])->whereIn('id', $listID);

        $query->whereHas('dataResource', function ($query) {
            // $query->filterStatusPublic();
        });

        return response()->json([
            'result' => $query->paginate(50, ['*'], 'page', $page),
        ], 200, []);
    }

    public function getListIdTaxon($rank, $query)
    {
        $taxon_query = DarwinCoreTaxon::query()->whereHas('dataResource', function ($query) {
            // $query->filterStatusPublic();
        })->where($rank . '_id', $query->{$rank . '_id'});
        $list_rank = ['kingdom', 'phylum', 'class', 'order', 'family', 'genus', 'species'];
        $key = array_search($rank, $list_rank);
        $down = $key;
        $up = $key;
        while ($down > 0) {
            --$down;
            $taxon_query->whereNotNull($list_rank[$down] . '_id');
        }
        $listID = $taxon_query->pluck('id');
        // dd($listID);

        return $listID;
    }
    public function getSpeciesImage(Request $request, $id)
    {
        $species = DarwinCoreTaxonTree::find($id);
        $listID = $this->getListIdTaxon($species->rank, $species);
        $query = DarwinCoreSimpleImage::with('darwinCoreTaxon', 'media')->whereIn('darwin_core_taxon_id', $listID);
        $data = $this->filterTrait($request, $query, [
            'isPagination' => true,
            'arrayFilter' => ['subject_id', 'subject_type'],
            'arraySort' => [],
            'arraySearch' => [],
        ]);
        return response()->json($data, 200, []);
    }

    public function getImage(Request $request, $id)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);
        $species = DarwinCoreTaxonTree::query()->where('id', $id)->first();
        $images = 0;
        $rank = $species->rank;
        $listID = $this->getListIdTaxon($rank, $species);
        $images = DarwinCoreSimpleImage::with('darwinCoreTaxon', 'media')->select(['id', 'identifier', 'darwin_core_taxon_id'])->whereIn('darwin_core_taxon_id', $listID);
        return response()->json(['images' => $images->paginate($perPage, ['*'], 'page', $page)], 200, []);
    }

    public function indexDetailSpecies($id)
    {
        return view('detail.detailspecies', [
            'lang' => Session::get('locale'),
            'id' => $id,
        ]);
    }

    public function indexDetailDataset($id)
    {
        return view('detail.detaildataset', [
            'lang' => Session::get('locale'),
            'id' => $id,
        ]);
    }

    public function detailRegion($id)
    {
        $region = DarwinCoreOccurrences::query()->select(['scientific_name', 'basis_of_record', 'locality', 'event_date', 'id', 'taxon_id', 'recorded_by', 'day', 'month', 'year', 'decimal_latitude', 'decimal_longitude', 'verbatim_elevation', 'individual_count', 'sampling_protocol', 'life_stage', 'reproductive_condition', 'behavior', 'dataset_name'])->where('id', $id)->first();
        return response()->json([
            'message' => 'Thành công',
            'result' => $region,
        ], 200, []);
    }

    public function indexDetailRegion($id)
    {
        return view('detail.detailregion', [
            'lang' => Session::get('locale'),
            'id' => $id,
        ]);
    }

    public function detailProtectedArea($id)
    {
        $protectedarea = ProtectedArea::query()->select(['wdpaid', 'wdpa_pid', 'name', 'orig_name', 'country', 'sub_loc', 'desig', 'desig_eng', 'desig_type', 'iucncat', 'marine', 'rep_m_area', 'rep_area', 'status', 'status_year', 'gov_type', 'mang_auth', 'int_crit', 'mang_plan', 'metadataid', 'longitude', 'latitude', 'legislation', 'description', 'id'])->where('id', $id)->first();
        return response()->json([
            'message' => 'Thành công',
            'result' => $protectedarea,
        ], 200, []);
    }

    public function indexDetailProtectedArea($id)
    {
        return view('detail.detailprotectedarea', [
            'lang' => Session::get('locale'),
            'id' => $id,
        ]);
    }
}
