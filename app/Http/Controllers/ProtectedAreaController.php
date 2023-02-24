<?php

namespace App\Http\Controllers;

use App\DarwinCoreTaxon;
use App\DatasetResource;
use App\District;
use App\OTieuChuan;
use App\ProtectedArea;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProtectedAreaController extends Controller
{
    public function indexView(Request $request)
    {
        $type = $request->get('type', 'all');
        return view('user.protectedarea.index', [
            'lang' => Session::get('locale'),
            'type' => $type,
        ]);
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $desigType = $request->get('desig_type');
        $subLocs = $request->get('sub_locs');
        $region_ids = $request->get('region_ids');
        $desigs = $request->get('desigs');
        $status = $request->get('status');
        $govType = $request->get('gov_type');
        $isInternational = $request->get('isInternational');
        $trang_thai = $request->get('trang_thai', null);
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $query = \App\ProtectedArea::query();
        $query->withCount(['datasetResources' => function ($query) {
            $query->filterStatusPublic();
        }]);

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('orig_name', 'ilike', "%{$search}%");
            });
        }
        if (!empty($desigType)) {
            $query->where('desig_type', $desigType);
        }
        if (!empty($region_ids)) {
            $query->whereIn('region_id', $region_ids);
        }
        if (!empty($subLocs)) {
            //$query->whereIn('sub_loc', $subLocs);
            $query->where(function ($q) use ($subLocs) {
                foreach ($subLocs as $quan_huyen_id) {
                    $q->orWhereRaw(DB::raw("quan_huyen::jsonb @> '[" . $quan_huyen_id . "]'"));
                }
            });
        }
        if (!empty($desigs)) {
            $query->whereIn('desig', $desigs);
        }
        if (!empty($status)) {
            $query->whereIn('status', $status);
        }
        if (!empty($govType)) {
            $query->whereIn('gov_type', $govType);
        }
        if ($isInternational) {
            $query->where('isInternational', true);
        }
        if($trang_thai && count($trang_thai)){
            $query->whereIn('status', $trang_thai);
        }
        $query->orderBy('name', 'asc');
        $fullData = $query->get();
        $count_species = [];
        foreach ($fullData as $item) {
            $id = $item['id'];
            $darwinCoreTaxon = $this->getListSpeciesQuery($id)->count();
            $count_species[$id] = $darwinCoreTaxon;
        }
        $result = $query->paginate($perPage, ['*'], 'page', $page);
        foreach($result as $item){
            $quanHuyen = '';
            if($item['quan_huyen']){
              $qhs =   District::whereIn('id', json_decode($item['quan_huyen']))->pluck('name_vietnamese')->toArray();
              $quanHuyen = implode(", ", $qhs);
            }
            $item['ten_quan_huyen'] = $quanHuyen;
        }
        return response()->json([
            'message' => 'Thành công',
            'fullData' => isset($user) ? $query->get() : [],
            'result' => $result,
            'count_species' => $count_species,
            'user' => isset($user) ? $user->role_id : '',
        ], 200, []);
    }
    public function show(Request $request, $id)
    {
        $query = \App\ProtectedArea::query();
        $query->with('region');
        $query->withCount(['datasetResources' => function ($query) {
            $query->filterStatusPublic();
        }]);
        $data = $query->findOrFail($id);
        $querySpecies = $this->getListSpeciesQuery($id);
        $data['species_count'] = $querySpecies->count();
        return $data;
    }
    public function detail(Request $request, $id)
    {
        $query = \App\ProtectedArea::query();
        $query->with('region');
        $query->withCount(['datasetResources' => function ($query) {
            $query->filterStatusPublic();
        }]);
        //get list of Species through list dataset (function at below)
        $listSpecies = $this->getListSpecies($request, $id);
        $tab = $request->get('tab', 1);
        $occurrencesDataset = \App\ProtectedArea::query()->where('protected_areas.id', $id)
            ->join('darwin_core_occurrences', 'darwin_core_occurrences.protected_area_id', '=', 'protected_areas.id')
            ->join('dataset_resources', 'darwin_core_occurrences.dataset_resource_id', '=', 'dataset_resources.id')
            ->groupBy('dataset_resources.id')
            ->select('dataset_resources.id', 'dataset_resources.title', DB::raw('count(darwin_core_occurrences.id) as count'))
            ->orderBy('count', 'DESC')->get();
        $occurrencesProvince = \App\ProtectedArea::query()->where('protected_areas.id', $id)
            ->join('darwin_core_occurrences', 'darwin_core_occurrences.protected_area_id', '=', 'protected_areas.id')
            ->join('provinces', 'darwin_core_occurrences.province_id', '=', 'provinces.id')
            ->groupBy('provinces.id')
            ->select('provinces.id', 'provinces.name_vietnamese', DB::raw('count(darwin_core_occurrences.id) as count'))
            ->orderBy('count', 'DESC')->get();
        $occurrencesRegion = \App\ProtectedArea::query()->where('protected_areas.id', $id)
            ->join('darwin_core_occurrences', 'darwin_core_occurrences.protected_area_id', '=', 'protected_areas.id')
            ->join('regions', 'darwin_core_occurrences.region_id', '=', 'regions.id')
            ->groupBy('regions.id')
            ->select('regions.id', 'regions.name_vietnamese', DB::raw('count(darwin_core_occurrences.id) as count'))
            ->orderBy('count', 'DESC')->get();
        $datasetOrga = \App\ProtectedArea::query()->where('protected_areas.id', $id)
            ->join('dataset_resource_areas', 'dataset_resource_areas.protected_area_id', '=', 'protected_areas.id')
            ->join('dataset_resources', 'dataset_resources.id', '=', 'dataset_resource_areas.dataset_resource_id')
            ->join('organizations', 'organizations.id', '=', 'dataset_resources.organization_id')
            ->groupBy('organizations.id')
            ->select('organizations.id', 'organizations.name', DB::raw('count(dataset_resources.id) as count'))
            ->orderBy('count', 'DESC')->get();
        $datasetWithYear = \App\ProtectedArea::query()->where('protected_areas.id', $id)
            ->join('darwin_core_occurrences', 'darwin_core_occurrences.protected_area_id', '=', 'protected_areas.id')
            ->get();
        $data = [];
        $year = Carbon::now()->year;
        for ($i = 2010; $i <= $year; $i++) {
            $array[0] = $i;
            $array[1] = $datasetWithYear->where('year', $i)->count();
            $data[] = $array;
        }
        $data_return = [
            'lang' => Session::get('locale'),
            'model' => !empty($query->find($id)) ? $query->with('media')->find($id) : null,
            'occurrencesDataset' => $occurrencesDataset,
            'occurrencesProvince' => $occurrencesProvince,
            'occurrencesRegion' => $occurrencesRegion,
            'datasetWithYear' => json_encode($data),
            'datasetOrga' => $datasetOrga,
            'listSpecies' => $listSpecies,
            'tab' => $tab,
        ];
        if ($request->wantsJson()) {
            return response()->json($data_return);
        }
        return view('user.protectedarea.detail', $data_return);
    }

    public function getListSpecies(Request $request, $id)
    {

        $page = $request->get('page', 1);
        return $this->getListSpeciesQuery($id)->paginate(20, ['*'], 'page', $page);
    }
    public function getListSpeciesQuery($protected_area_id)
    {
        $datasetProtectedArea = DatasetResource::query()->filterStatusPublic();
        $datasetProtectedArea->select('id')->whereHas('datasetResourceArea', function ($datasetProtectedArea) use ($protected_area_id) {
            $datasetProtectedArea->where('protected_area_id', $protected_area_id);
        });
        $datasetProtectedArea = $datasetProtectedArea->pluck('id')->toArray();
        $darwinCoreTaxon = DarwinCoreTaxon::with([
            'kingDom:name,id,name_vietnamese',
            'phylum:name,id,name_vietnamese',
            'class:name,id,name_vietnamese',
            'order:name,id,name_vietnamese',
            'family:name,id,name_vietnamese',
            'genus:name,id,name_vietnamese',
            'species:name,id,name_vietnamese',
            'dataResource:id,title',
        ])->whereIn('dataset_resource_id', $datasetProtectedArea)->select([
            'id', 'scientific_name', 'dataset_resource_id', 'kingdom_id', 'phylum_id', 'class_id', 'order_id', 'family_id',
            'genus_id', 'species_id', 'dataset_resource_id',
        ])->orderBy('scientific_name');
        return $darwinCoreTaxon;
    }

    public function getTopsubloc(Request $request)
    {
        // $limit = $request->get('limit', 10);
        // $region_ids = $request->get('region_ids');
        // $query = \App\ProtectedArea::query();
        // if (!empty($region_ids)) {
        //     $query->whereIn('region_id', $region_ids);
        // }
        // $query->groupBy('sub_loc')
        //     ->select('sub_loc', DB::raw('count(id) as count'));
        // if (!is_numeric($limit)) {
        //     $limit = 10;
        // }
        // $query->limit($limit);
        // $query->orderBy('count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => District::select('id', 'name_vietnamese as sub_loc')->get(),
        ], 200, []);
    }

    public function getTopDesig(Request $request)
    {
        $limit = $request->get('limit', 10);
        $query = \App\ProtectedArea::query();
        $query->groupBy('desig')
            ->select('desig', DB::raw('count(id) as count'));
        if (!is_numeric($limit)) {
            $limit = 10;
        }
        $query->limit($limit);
        $query->orderBy('count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getTopStatus(Request $request)
    {
        $getTop = $request->get('getTop');
        $query = \App\ProtectedArea::query();
        $query->groupBy('status')->select('status', DB::raw('count(id) as count'));
        if (empty($getTop)) {
            $limit = $request->get('limit', 10);
            if (!is_numeric($limit)) {
                $limit = 10;
            }
            $query->limit($limit);
        }
        $query->orderBy('count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getTopGovType(Request $request)
    {
        $getTop = $request->get('getTop');
        $query = \App\ProtectedArea::query();
        $query->whereNotNull('gov_type')->groupBy('gov_type')
            ->select('gov_type', DB::raw('count(id) as count'));
        if (empty($getTop)) {
            $limit = $request->get('limit', 10);
            if (!is_numeric($limit)) {
                $limit = 10;
            }
            $query->limit($limit);
        }
        $data = [
            [
                "gov_type" => "Ủy ban nhân dân thành phố",
            ],
            [
                "gov_type" => "Trung ương",
            ],
        ];
        $query->orderBy('count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function asynchronous(Request $request)
    {
        $search = $request->get('search');
        $limit = $request->get('limit', 10);
        $query = \App\ProtectedArea::query();
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('orig_name', 'ilike', "%{$search}%");
            });
        }
        $query->select('id', 'orig_name', 'name');
        if (!is_numeric($limit)) {
            $limit = 10;
        }
        $query->limit($limit);
        $query->orderBy('orig_name');
        $query->orderBy('name');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getSublocs(Request $request)
    {
        $region_ids = $request->get('region_ids');
        $search = $request->get('search');
        $query = \App\ProtectedArea::query()->select('sub_loc');
        if (!empty($region_ids)) {
            $query->whereIn('region_id', $region_ids);
        }
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('sub_loc', 'ilike', "%{$search}%");
            });
        }
        $query->groupBy('sub_loc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getDesigs(Request $request)
    {
        $search = $request->get('search');
        $query = \App\ProtectedArea::query()->select('desig');
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('desig', 'ilike', "%{$search}%");
            });
        }
        $query->groupBy('desig');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getDesigType(Request $request)
    {
        $query = \App\ProtectedArea::query();
        $query->whereNotNull('desig_type')->groupBy('desig_type')
            ->select('desig_type', DB::raw('count(id) as count'));
        $query->orderBy('count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getMangAuth()
    {
        $query = \App\ProtectedArea::query();
        $query->groupBy('mang_auth')->select('mang_auth');
        $query->orderBy('mang_auth', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getDataMap()
    {
        $query = \App\ProtectedArea::where('geometry', '<>', null)->select('id', 'orig_name', 'desig', 'geometry')->get();
        return response()->json([
            'data' => $query,
            'legends' => [
                [
                    'id'=>1,
                    'name'=>"Khu bảo vệ cảnh quan",
                ],
                [
                    'id'=>2,
                    'name'=>"Vườn quốc gia",
                ]
            ],
        ], 200, []);
    }
    public function indexImage(Request $request, $id)
    {
        $query = \App\ProtectedArea::query();
        $data = $query->findOrFail($id);
        return response()->json([
            'message' => __('message.success'),
            'result' => $data->getMedia(),
        ], 200, []);
    }

    public function indexViewStandardZone()
    {
        return view('user.protectedarea.standardzone', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function indexViewCoSoBaoTon()
    {
        return view('user.protectedarea.cosobaoton', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function indexViewHanhLang()
    {
        return view('user.protectedarea.hanhlang', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function indexViewHighBiodiversity()
    {
        return view('user.protectedarea.highbiodiversity', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function indexViewImportantWetland()
    {
        return view('user.protectedarea.webland', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function indexViewImportantBirdAre()
    {
        return view('user.protectedarea.birdarea', [
            'lang' => Session::get('locale'),
        ]);
    }
    public function indexViewImportantLandscape()
    {
        return view('user.protectedarea.landscape', [
            'lang' => Session::get('locale'),
        ]);
    }
    public function indexViewBiosphere()
    {
        return view('user.protectedarea.biosphere', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function getOTieuChuan(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $khu_bao_ton = $request->get('khu_bao_ton', null);
        $query = OTieuChuan::with(['diaDiem']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('khu_sinh_thai', 'ilike', "%{$search}%")
                    ->orWhere('vi_tri', 'ilike', "%{$search}%")
                    ->orWhere('hinh_Dang', 'ilike', "%{$search}%")
                    ->orWhere('do_cao', 'ilike', "%{$search}%");
            });
        }
        if ($khu_bao_ton) {
            $query->whereIn('dia_diem_id', $khu_bao_ton);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function getTopPhanBo(){
        $quan = District::select('id', 'name_vietnamese')->pluck('name_vietnamese')->toArray();
        $khac  = ["Hồ",  "đầm",  "Ruộng trũng", "Sông", "Suối"];
        $phanBo = array_merge($quan, $khac);
        $data = [];
        foreach($phanBo as $key => $item){
            $data[] = ['id' => $key, 'phan_bo' => $item] ;
        }
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function getSearchPhanBo(Request $request){
        $search = $request->get('search', null);
        $quan = District::query();
        $khac  = ["Hồ",  "đầm",  "Ruộng trũng", "Sông", "Suối"];
        if($search){
            $quan = $quan->where('name_vietnamese','ilike', "%{$search}%");
        }
        $quan = $quan->select('id', 'name_vietnamese')->pluck('name_vietnamese')->toArray();
        $phanBo = array_merge($quan, $khac);
        $data = [];
        foreach($phanBo as $key => $item){
            $data[] = ['id' => $key, 'phan_bo' => $item] ;
        }
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }
}
