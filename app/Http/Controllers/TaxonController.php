<?php

namespace App\Http\Controllers;

use App\DarwinCoreSimpleImage;
use App\DatasetResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TaxonController extends Controller
{
    public function detail($id)
    {

        $query = \App\DarwinCoreTaxon::query()->with([
            'kingDom:name,id,name_vietnamese',
            'phylum:name,id,name_vietnamese',
            'class:name,id,name_vietnamese',
            'order:name,id,name_vietnamese',
            'family:name,id,name_vietnamese',
            'genus:name,id,name_vietnamese',
            'species:name,id,name_vietnamese',
            'dataResource:id,title,intellectual_rights,citation,geographic_description,organization_id,original_filename',
            'dataResource.organization:id,name',
            'nbdsTaxonExtension'
        ]);
        // $query->whereHas('dataResource',function($query){
        //     $query->filterStatusPublic();
        // });
        $images = DarwinCoreSimpleImage::with('darwinCoreTaxon')->select(['id', 'identifier', 'darwin_core_taxon_id'])->where('darwin_core_taxon_id', $id)->count();
        return view('user.taxon.detail', [
            'lang' => Session::get('locale'),
            'data' => !empty($query->find($id)) ? $query->find($id)->toJson() : null,
            'images' => $images
        ]);
    }


    public function index(Request $request)
    {
        $data = $this->getData($request, 'index');
        return response()->json(array_merge(['message' => 'Thành công'], $data), 200, []);
    }
    public function getData(Request $request, $type)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $limit = $request->get('limit');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);
        $filterDatasets = $request->get('datasets');
        $khu_bao_ton_id = $request->get('khu_bao_ton_id', null);
        $co_so_bao_ton_id = $request->get('co_so_bao_ton_id', null);
        $query = \App\DarwinCoreTaxon::query();
        $query->whereHas('dataResource', function ($query) {
            $query->filterStatusPublic();
        });
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('scientific_name', 'ilike', "%{$search}%");
                $query->orWhere('_search', 'ilike', "%{$search}%");
                $query->orWhere(function ($query) use ($search) {
                    $query->whereHas('dataResource', function ($query) use ($search) {
                        $query->where('title', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhereHas('kingDom', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhereHas('phylum', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhereHas('class', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhereHas('order', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhereHas('family', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhereHas('genus', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhereHas('species', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
            });
        }
        if (!empty($filterDatasets) && is_array($filterDatasets)) {
            $query->whereIn('dataset_resource_id', $filterDatasets);
        }
        if($khu_bao_ton_id){  
            $dataset = DatasetResource::where('loai_doi_tuong', 'khu_bao_ton')->where('doi_tuong_id', $khu_bao_ton_id)->pluck('id')->toArray();
            $query->whereIn('dataset_resource_id', $dataset);
        }
        if($co_so_bao_ton_id){
            $dataset = DatasetResource::where('loai_doi_tuong', 'co_so_bao_ton')->where('doi_tuong_id', $co_so_bao_ton_id)->pluck('id')->toArray();
            $query->whereIn('dataset_resource_id', $dataset);
        }
        $query->with([
            'kingDom:name,id,name_vietnamese',
            'phylum:name,id,name_vietnamese',
            'class:name,id,name_vietnamese',
            'order:name,id,name_vietnamese',
            'family:name,id,name_vietnamese',
            'genus:name,id,name_vietnamese',
            'species:name,id,name_vietnamese',
            'dataResource:id,title',
            'nbdsTaxonExtension'
        ]);
        $query->select([
            'id', 'scientific_name', 'dataset_resource_id', 'kingdom_id', 'phylum_id', 'class_id', 'order_id', 'family_id',
            'genus_id', 'species_id', 'dataset_resource_id', 'vernacular_name_english'
        ]);
        $query->orderBy('scientific_name');
        if (!empty($limit) && is_numeric($limit)) {
            $query->limit($limit);
        }
        switch ($type) {
            case 'index':
                return [
                    'result' => $query->paginate($perPage, ['*'], 'page', $page),
                    'user' => isset($user) ? $user->role_id : ''
                ];
                break;
            case 'images':
                $listID = $query->pluck('id');
                return $listID;
                break;
            default:
                break;
        }
    }

    public function getImage(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);
        $listID = $this->getData($request, 'images');
        $id = $request->get('id');
        if (isset($id)) {
            $images = DarwinCoreSimpleImage::with('darwinCoreTaxon')->select(['id', 'identifier', 'darwin_core_taxon_id'])->where('darwin_core_taxon_id', $id);
        } else {
            $images = DarwinCoreSimpleImage::with('darwinCoreTaxon')->select(['id', 'identifier', 'darwin_core_taxon_id'])->whereIn('darwin_core_taxon_id', $listID);
        }
        return response()->json(['total' => $images->count(), 'images' => $images->paginate($perPage, ['*'], 'page', $page)], 200, []);
    }
    public function indexView(Request $request)
    {
        $datasetId = $request->get('dataset');
        $dataset = (object) [];
        if (!empty($datasetId)) {
            $dataset = \App\DarwinCoreTaxon::query();
            $dataset->whereHas('dataResource', function ($query) {
                $query->filterStatusPublic();
            });
            $dataset->with([
                'dataResource:id,title,intellectual_rights,citation,geographic_description',
            ]);
            $dataset->select('title', 'id')->find($datasetId);
        }

        return view('user.taxon.index', [
            'lang' => Session::get('locale'),
            'dataset' => $dataset,
        ]);
    }

    public function getTopDatasets(Request $request)
    {

        $limit = $request->get('limit', 10);
        $query = \App\DatasetResource::query()->filterStatusPublic();
        $query->select('id', 'title');
        $query->has('darwinCoreTaxons')
            ->withCount('darwinCoreTaxons');
        if (!is_numeric($limit)) {
            $limit = 10;
        }

        $query->limit($limit);
        $query->orderBy('darwin_core_taxons_count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getDatasetresources(Request $request)
    {
        $dataset_resources_id = $request->get('dataset_resources_id');
        $query = \App\DatasetResource::query()->where('id', $dataset_resources_id);
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }
}
