<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PublisherController extends Controller
{
    public function getTopPublisher()
    {
        $query = \App\Organization::query();
        $query->select('name', 'name_vietnamese', 'id')
            ->has('datasetResources')
            ->withCount(['datasetResources' => function ($query) {
                $query->filterStatusPublic();
            }])
            ->orderBy('dataset_resources_count', 'desc')
            ->limit(10);
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }
    public function getResourceType()
    {
        $query = \App\Resource::query();
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function asynchronous(Request $request)
    {
        $search = $request->get('search');
        $withCount = $request->get('withCount');
        $query = \App\Organization::query();
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
            });
        }
        $query->select(['name', 'name_vietnamese', 'id']);
        if (isset($withCount)) {
            $query->withCount($withCount);
        }
        $query->orderBy('name');
        $query->orderBy('name_vietnamese');

        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $organization_type = $request->get('organization_type');

        $withs = $request->get('withs');
        $withcounts = $request->get('with_counts');
        $selectFields = $request->get('select_fields', ['name', 'name_vietnamese', 'id']);
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);
        $query = \App\Organization::query();
        $publisher = $request->get('publisher');
        if (!empty($publisher)) {
            $query->whereIn('id', $publisher);
        }if (!empty($organization_type)) {
            $query->whereIn('organization_type', $organization_type);
        }
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
            });
        }
        if (is_array($selectFields)) {
            $model = new \App\Organization();
            $fields = array_intersect($selectFields, $model->getFillable());
            if (!empty($fields)) {
                $query->select($fields);
                if (!in_array('id', $fields)) {
                    $query->addSelect('id');
                }
            }
            if (in_array('name', $fields)) {
                $query->orderBy('name');
            }
            if (in_array('name_vietnamese', $fields)) {
                $query->orderBy('name_vietnamese');
            }
        }
        if (is_array($withcounts)) {

            $withcounts = array_intersect($withcounts, ['datasetResources']);

            if (!empty($withcounts)) {
                $query->withCount(['datasetResources' => function ($query) {
                    $query->filterStatusPublic();
                }]);
            }
        }
        if (is_array($withs)) {
            $withs = array_intersect($withs, ['parent', 'datasetResources']);
            if (!empty($withs)) {
                if (in_array('parent', $withs)) {
                    $query->addSelect('parent_organization_id');
                }
                $query->with($withs);
            }
        }
        $query->with('mediable.media');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->paginate($perPage, ['*'], 'page', $page),
        ], 200, []);
    }

    public function indexView(Request $request)
    {
        $type = $request->get('type', 'all');
        $publisher = $request->get('publisher');

        return view('user.publisher.index', [
            'lang' => Session::get('locale'),
            'type' => $type,
            'publisher' => $publisher,
        ]);
    }

    public function detail(Request $request, $id)
    {
        $query = \App\Organization::query();
        $query->withCount(['datasetResources' => function ($query) {
            $query->filterStatusPublic();
        }]);
        $query->with(['parent']);
        $getImageUrl = $query->find($id)->getFirstMediaUrl();
        $datasetWithYear = \App\Organization::query()->where('organizations.id', $id)
            ->join('dataset_resources', 'dataset_resources.organization_id', '=', 'organizations.id')
            ->join('darwin_core_occurrences', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
            ->get();
        $data = [];
        $year = Carbon::now()->year;
        for ($i = 2010; $i <= $year; $i++) {
            $array[0] = $i;
            $array[1] = $datasetWithYear->where('year', $i)->count();
            $data[] = $array;
        }
        $occurrencesDataset = \App\Organization::query()->where('organizations.id', $id)
            ->join('dataset_resources', 'dataset_resources.organization_id', '=', 'organizations.id')
            ->join('darwin_core_occurrences', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
            ->groupBy('dataset_resources.id')
            ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(darwin_core_occurrences.id) as count'))
            ->orderBy('count', 'DESC')->get();
        $occurrencesProtectedArea = \App\Organization::query()->where('organizations.id', $id)
            ->join('dataset_resources', 'dataset_resources.organization_id', '=', 'organizations.id')
            ->join('darwin_core_occurrences', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
            ->join('protected_areas', 'darwin_core_occurrences.protected_area_id', '=', 'protected_areas.id')
            ->groupBy('protected_areas.id')
            ->select('protected_areas.id', 'protected_areas.name', DB::raw('count(darwin_core_occurrences.id) as count'))
            ->orderBy('count', 'DESC')->get();

        $occurrencesRegion = \App\Organization::query()->where('organizations.id', $id)
            ->join('dataset_resources', 'dataset_resources.organization_id', '=', 'organizations.id')
            ->join('darwin_core_occurrences', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
            ->join('regions', 'darwin_core_occurrences.region_id', '=', 'regions.id')
            ->groupBy('regions.id')
            ->select('regions.id', 'regions.name_vietnamese', DB::raw('count(darwin_core_occurrences.id) as count'))
            ->orderBy('count', 'DESC')->get();

        return view('user.publisher.detail', [
            'lang' => Session::get('locale'),
            'model' => $query->find($id)->toJson(),
            'occurrencesDataset' => $occurrencesDataset,
            'occurrencesRegion' => $occurrencesRegion,
            'occurrencesProtectedArea' => $occurrencesProtectedArea,
            'datasetWithYear' => json_encode($data),
            'image' => !empty($getImageUrl) ? $getImageUrl : '',
        ]);
    }

    public function getOrganizationType(Request $request)
    {
        $search = $request->get('search');
        $query = \App\Organization::query()
            ->select('organization_type', DB::raw('count(*) as count'))->whereNotNull('organization_type');
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('organization_type', 'ilike', "%{$search}%");
            });
        }
        $query->groupBy('organization_type')->orderBy('count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }
}
