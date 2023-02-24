<?php

namespace App\Http\Controllers;

use App\DarwinCoreOccurrences;
use App\DarwinCoreSimpleImage;
use App\DatasetResource;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class DatasetResourceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $limit = $request->get('limit');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);
        $filterPublishers = $request->get('publishers');
        $filterProtectedAreas = $request->get('protected_area');
        $filterDatasetType = $request->get('dataset_type');
        $filterYears = $request->get('filter_years');
        $resource_ids = $request->get('resource_ids');

        $query = DatasetResource::query();
        $filter_dataset_inheritance_type = $request->get('filter_dataset_inheritance_type');

        if (isset($filter_dataset_inheritance_type) && count($filter_dataset_inheritance_type) == 1) {
            foreach ($filter_dataset_inheritance_type as $key => $value) {
                if ($value == 'Public') {
                    $query->where('status', 'public');
                } else {
                    $query->where('status', 'draft');
                }
            }
        }
        foreach (["kingdom", "phylum", "class", "order", "family", "genus", "species"] as $key) {
            $keyId = $key . "_id";

            $valueSearch = $request->get($keyId);

            if (isset($valueSearch)) {
                $query->whereHas("darwinCoreTaxons", function ($query) use ($valueSearch, $keyId) {
                    $query->where($keyId, $valueSearch);
                });
            }
        }

        if (isset($resource_ids)) {
            $query->whereHas("darwinCoreTaxons", function ($query) use ($resource_ids) {
                $query->whereIn('resource_id', $resource_ids);
            });
        }

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('title', 'ilike', "%{$search}%");
            });
        }
        if (!empty($filterPublishers) && is_array($filterPublishers)) {
            $query->whereIn('organization_id', $filterPublishers);
        }
        if (!empty($filterProtectedAreas) && is_array($filterProtectedAreas)) {
            $query->whereHas('datasetResourceArea', function ($query) use ($filterProtectedAreas) {
                $query->whereIn('protected_area_id', $filterProtectedAreas);
            });
        }
        if (!empty($filterDatasetType)) {
            $query->where('dataset_type', $filterDatasetType);
        }
        $query->with(['organization:name,id']);
        $query->select('title', 'publication_date', 'dataset_type', 'id', 'geographic_description', 'organization_id');
        $query->withCount('darwinCoreOccurrences', 'darwinCoreTaxons', 'datasetReferences');
        $query->orderBy('title');
        if (!empty($limit) && is_numeric($limit)) {
            $query->limit($limit);
        }
        if (!empty($filterYears) && is_array($filterYears)) {
            foreach ($filterYears as $filter) {
                $filter = json_decode($filter);
                if (!empty($filter->option) && !empty($filter->value) && is_array($filter->value)) {
                    $value = $filter->value;
                    switch ($filter->option) {
                        case "between":
                            if (count($value) > 1) {
                                $query->where('publication_date', '>=', $value[0]);
                                $query->where('publication_date', '<=', $value[1]);
                            }
                            break;
                        case "is":
                            $query->where('publication_date', '=', $value[0]);
                            break;
                        case "before_end_of":
                            $query->where('publication_date', '<=', $value[0]);
                            break;
                        case "after_start_of":
                            $query->where('publication_date', '>=', $value[0]);
                            break;
                        default:
                    }
                }
            }
        }

        return response()->json([
            'message' => 'Thành công',
            'result' => $query->paginate($perPage, ['*'], 'page', $page),
            // 'result' => $query->get(),
        ], 200, []);
    }

    public function getImage(Request $request, $id)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);
        $query = DatasetResource::findOrFail($id);
        $listID = $query->darwinCoreTaxons()->pluck('darwin_core_taxons.id');
        $images = DarwinCoreSimpleImage::with('darwinCoreTaxon')->select(['id', 'identifier', 'darwin_core_taxon_id'])->whereIn('darwin_core_taxon_id', $listID);
        return response()->json(['images' => $images->paginate($perPage, ['*'], 'page', $page)], 200, []);
    }

    public function asyncSearch(Request $request)
    {
        $search = $request->get('search');
        $limit = $request->get('limit', 10);
        $query = \App\DatasetResource::query();
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('title', 'ilike', "%{$search}%");
            });
        }
        $query->select('id', 'title');
        if (!is_numeric($limit)) {
            $limit = 10;
        }
        $query->limit($limit);
        $query->orderBy('title');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function detail(Request $request, $id)
    {
        $user = Auth::user();
        $query = \App\DatasetResource::query();
        $query->with(
            'organization:id,name,name_vietnamese,contacts,email,organization_type,url,address,tel,portal_url_element',
            'datasetResourceArea.protectedArea:name,id,orig_name',
            'datasetReferences:name,id,dataset_resource_id'
        );
        $query->withCount('darwinCoreOccurrences', 'darwinCoreTaxons', 'darwinCoreTaxons');

        $kingDoms = \App\KingDom::query()->whereHas('darwinCoreTaxons', function ($query) use ($id) {
            $query->where('dataset_resource_id', $id);
        })->select('id', 'name_vietnamese', 'name')->orderBy('name');
        $phylums = \App\PhyLum::query()->whereHas('darwinCoreTaxons', function ($query) use ($id) {
            $query->where('dataset_resource_id', $id);
        })->select('id', 'name_vietnamese', 'name')->orderBy('name');

        $Occurrences = DarwinCoreOccurrences::query()->where('dataset_resource_id', $id)
            ->select('id', 'scientific_name as ten', 'decimal_latitude as vi_do', 'decimal_longitude as kinh_do')
            ->orderBy('scientific_name');

        $year = Carbon::now()->year;

        $datasetWithYear = DarwinCoreOccurrences::where('dataset_resource_id', $id)->get();
        $data = [];
        for ($i = 2010; $i <= $year; $i++) {
            $array[0] = $i;
            $array[1] = $datasetWithYear->where('year', $i)->count();
            $data[] = $array;
        }
        $datasetWithProvin = DarwinCoreOccurrences::query()->where('dataset_resource_id', $id);
        $datasetWithProvin->with('province:id,name')->groupBy('province_id')->select('province_id', DB::raw('count(id) as count'));

        $datatree = DarwinCoreOccurrences::query()->where('darwin_core_occurrences.dataset_resource_id', $id);
        $datatree->join('darwin_core_taxons', 'darwin_core_occurrences.darwin_core_taxon_id', '=', 'darwin_core_taxons.id');
        $datatree->leftJoin('kingdoms', 'kingdoms.id', '=', 'darwin_core_taxons.kingdom_id')
            ->groupBy('darwin_core_taxons.kingdom_id', 'kingdoms.name')
            ->select('kingdom_id', 'kingdoms.name', DB::raw("'kingdomKey' as dimension"), DB::raw("'0' as phylums"), DB::raw('count(darwin_core_taxons.id) as count'))
            ->orderBy('count', 'DESC');

        $queryCount = DatasetResource::findOrFail($id);
        $listID = $queryCount->darwinCoreTaxons()->pluck('darwin_core_taxons.id');
        $images = DarwinCoreSimpleImage::with('darwinCoreTaxon')->select(['id', 'identifier', 'darwin_core_taxon_id'])->whereIn('darwin_core_taxon_id', $listID)->count();
        return view('user.dataset.detail', [
            'lang' => Session::get('locale'),
            'model' => !empty($query->find($id)) ? $query->find($id)->toJson() : null,
            'king_doms' => $kingDoms->get()->toJson(),
            'phylums' => $phylums->get()->toJson(),
            'occurrences' => $Occurrences->get()->toJson(),
            'datasetWithYear' => json_encode($data),
            'datasetWithProvin' => $datasetWithProvin->get()->toJson(),
            'user' => isset($user) ? $user->role_id : '',
            'datatree' => $datatree->get()->toJson(),
            'images' => $images,
        ]);
    }

    public function indexView(Request $request)
    {
        $type = $request->get('type', 'all');
        $publisher = $request->get('publisher');
        $protectedArea = $request->get('protected_area');
        $publisherObject = (object) [];
        if (!empty($publisher)) {
            $publisherObject = \App\Organization::query()->select('name', 'name_vietnamese', 'id')->find($publisher);
        }
        $protectedAreaObject = (object) [];
        if (!empty($protectedArea)) {
            $protectedAreaObject = \App\ProtectedArea::query()->select('name', 'orig_name', 'id')->find($protectedArea);
        }
        return view('user.dataset.index', [
            'lang' => Session::get('locale'),
            'type' => $type,
            'publisher' => $publisherObject,
            'protectedarea' => $protectedAreaObject,
        ]);
    }

    public function infoTree(Request $request)
    {
        $dimension = $request->get('dimension');
        $kingdom_id = $request->get('kingdom_id');
        $phylum_id = $request->get('phylum_id');
        $class_id = $request->get('class_id');
        $order_id = $request->get('order_id');
        $family_id = $request->get('family_id');
        $genus_id = $request->get('genus_id');
        $dataset_resource_id = $request->get('dataset_resource_id');
        $datatree = DarwinCoreOccurrences::query()->where('darwin_core_occurrences.dataset_resource_id', $dataset_resource_id);
        switch ($dimension) {
            case "kingdomKey":
                $datatree->join('darwin_core_taxons', 'darwin_core_occurrences.darwin_core_taxon_id', '=', 'darwin_core_taxons.id');
                $datatree->where('darwin_core_taxons.kingdom_id', $kingdom_id);
                $datatree->leftJoin('phylums', 'phylums.id', '=', 'darwin_core_taxons.phylum_id')
                    ->groupBy('darwin_core_taxons.phylum_id', 'phylums.name')
                    ->select('phylum_id', 'phylums.name', DB::raw("'phylumKey' as dimension"), DB::raw("'0' as classes"), DB::raw('count(darwin_core_taxons.id) as count'))
                    ->orderBy('count', 'DESC');
                break;
            case "phylumKey":
                $datatree->join('darwin_core_taxons', 'darwin_core_occurrences.darwin_core_taxon_id', '=', 'darwin_core_taxons.id');
                $datatree->where('darwin_core_taxons.kingdom_id', $kingdom_id);
                $datatree->where('darwin_core_taxons.phylum_id', $phylum_id);
                $datatree->leftJoin('classes', 'classes.id', '=', 'darwin_core_taxons.class_id')
                    ->groupBy('darwin_core_taxons.class_id', 'classes.name')
                    ->select('class_id', 'classes.name', DB::raw("'classKey' as dimension"), DB::raw("'0' as orders"), DB::raw('count(darwin_core_taxons.id) as count'))
                    ->orderBy('count', 'DESC');
                break;
            case "classKey":
                $datatree->join('darwin_core_taxons', 'darwin_core_occurrences.darwin_core_taxon_id', '=', 'darwin_core_taxons.id');
                $datatree->where('darwin_core_taxons.kingdom_id', $kingdom_id);
                $datatree->where('darwin_core_taxons.phylum_id', $phylum_id);
                $datatree->where('darwin_core_taxons.class_id', $class_id);
                $datatree->leftJoin('orders', 'orders.id', '=', 'darwin_core_taxons.order_id')
                    ->groupBy('darwin_core_taxons.order_id', 'orders.name')
                    ->select('order_id', 'orders.name', DB::raw("'orderKey' as dimension"), DB::raw("'0' as families"), DB::raw('count(darwin_core_taxons.id) as count'))
                    ->orderBy('count', 'DESC');
                break;
            case "orderKey":
                $datatree->join('darwin_core_taxons', 'darwin_core_occurrences.darwin_core_taxon_id', '=', 'darwin_core_taxons.id');
                $datatree->where('darwin_core_taxons.kingdom_id', $kingdom_id);
                $datatree->where('darwin_core_taxons.phylum_id', $phylum_id);
                $datatree->where('darwin_core_taxons.class_id', $class_id);
                $datatree->where('darwin_core_taxons.order_id', $order_id);
                $datatree->leftJoin('families', 'families.id', '=', 'darwin_core_taxons.family_id')
                    ->groupBy('darwin_core_taxons.family_id', 'families.name')
                    ->select('family_id', 'families.name', DB::raw("'familyKey' as dimension"), DB::raw("'0' as genus"), DB::raw('count(darwin_core_taxons.id) as count'))
                    ->orderBy('count', 'DESC');
                break;
            case "familyKey":
                $datatree->join('darwin_core_taxons', 'darwin_core_occurrences.darwin_core_taxon_id', '=', 'darwin_core_taxons.id');
                $datatree->where('darwin_core_taxons.kingdom_id', $kingdom_id);
                $datatree->where('darwin_core_taxons.phylum_id', $phylum_id);
                $datatree->where('darwin_core_taxons.class_id', $class_id);
                $datatree->where('darwin_core_taxons.order_id', $order_id);
                $datatree->where('darwin_core_taxons.family_id', $family_id);
                $datatree->leftJoin('genus', 'genus.id', '=', 'darwin_core_taxons.genus_id')
                    ->groupBy('darwin_core_taxons.genus_id', 'genus.name')
                    ->select('genus_id', 'genus.name', DB::raw("'genusKey' as dimension"), DB::raw("'0' as species"), DB::raw('count(darwin_core_taxons.id) as count'))
                    ->orderBy('count', 'DESC');
                break;
            case "genusKey":
                $datatree->join('darwin_core_taxons', 'darwin_core_occurrences.darwin_core_taxon_id', '=', 'darwin_core_taxons.id');
                $datatree->where('darwin_core_taxons.kingdom_id', $kingdom_id);
                $datatree->where('darwin_core_taxons.phylum_id', $phylum_id);
                $datatree->where('darwin_core_taxons.class_id', $class_id);
                $datatree->where('darwin_core_taxons.order_id', $order_id);
                $datatree->where('darwin_core_taxons.family_id', $family_id);
                $datatree->where('darwin_core_taxons.genus_id', $genus_id);
                $datatree->leftJoin('species', 'species.id', '=', 'darwin_core_taxons.species_id')
                    ->groupBy('darwin_core_taxons.species_id', 'species.name')
                    ->select('species_id', 'species.name', DB::raw("'speciesKey' as dimension"), DB::raw("'0' as sub_species"), DB::raw('count(darwin_core_taxons.id) as count'))
                    ->orderBy('count', 'DESC');
                break;
            default:
        }

        return response()->json([
            'message' => __('message.success'),
            'datatree' => $datatree->get(),
        ], 200, []);
    }

    public function infoPercent(Request $request)
    {
        $dataset_resource_id = $request->get('dataset_resource_id');
        $count_taxon = DarwinCoreOccurrences::query()->where('dataset_resource_id', $dataset_resource_id)
            ->where('darwin_core_taxon_id', '!=', null)->count();

        $count_coordinates = DarwinCoreOccurrences::query()->where('dataset_resource_id', $dataset_resource_id)
            ->where('decimal_latitude', '!=', null)->count();

        $count_year = DarwinCoreOccurrences::query()->where('dataset_resource_id', $dataset_resource_id)
            ->where('year', '!=', null)->count();

        return response()->json([
            'message' => __('message.success'),
            'count_taxon' => $count_taxon,
            'count_coordinates' => $count_coordinates,
            'count_year' => $count_year,
        ], 200, []);
    }
}
