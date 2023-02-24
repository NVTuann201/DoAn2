<?php

namespace App\Http\Controllers\Admin;

use App\Classes;
use App\CoSoBaoTon;
use App\Organization;
use App\OrgDefinedFormat;
use Illuminate\Http\Request;
use App\DarwinCoreTaxon;
use App\DarwinCoreOccurrences;
use App\Province;
use App\District;
use App\Region;
use App\ProtectedArea;
use App\DatasetResourceArea;
use App\Species;
use App\Traits\GetDataCache;
use App\Traits\ExecuteExcel;
use App\Traits\ExecuteString;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\DatasetResource;
use App\Family;
use App\KingDom;
use App\Lookup;
use App\NbdsTaxonExtension;
use App\Order;
use App\PhyLum;
use App\SubSpecie;
use App\Synonym;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DatasetController extends Controller
{
    use GetDataCache, ExecuteExcel, ExecuteString;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.category.dataset.index');
        }
    }

    public function getDataset(Request $request)
    {
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search');
        $search_organization = $request->get('search_organization');
        $search_data_type = $request->get('search_dataset_type');
        $search_status = $request->get('search_status');

        $query = DatasetResource::query();

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('title', 'ilike', "%{$search}%");
            });
        }
        if (isset($search_organization)) {
            $query->where('organization_id', $search_organization);
        }
        if (isset($search_data_type)) {
            $query->where('dataset_type', $search_data_type);
        }
        if (isset($search_status)) {
            if ($search_status == 'public') {
                $query->where('status', 'public');
            } else {
                $query->where('status', 'draft');
            }
        }
        $query->orderBy('title')->with('organization:id,name');

        $dataset = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => __('message.success'),
            'result' => $dataset,
        ], 200, []);
    }

    public function getStatus(Request $request)
    {
        $query = DatasetResource::query();
        $queryClone = clone $query;
        $status = [];
        $status['public'] = $queryClone->where('status', 'public')->orWhere('publication_date', '>=', Carbon::create(2019, 1, 1, 0, 0, 0))->count();
        $status['draft'] = $query->count() - $status['public'];
        return response()->json([
            'message' => __('message.success'),
            'result' => $status
        ], 200, []);
    }

    public function getTopOrganization(Request $request)
    {
        $query = DatasetResource::query();
        $search = $request->get('search');
        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('title', 'ilike', "%{$search}%");
            });
        }
        $query->groupBy('organization_id')->select('organization_id', DB::raw('count(id) as count'));
        $query->limit(5);
        $query->orderBy('count', 'desc');
        $query->with('organization');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getDatasetType(Request $request)
    {
        $query = DatasetResource::query();
        $search = $request->get('search');
        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('title', 'ilike', "%{$search}%");
            });
        }
        $query->groupBy('dataset_type')->select('dataset_type', DB::raw('count(id) as count'));
        $query->orderBy('count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.dataset.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'title' => 'required|max:255|unique:dataset_resources',
            'language' => 'required|max:255',
            'organization_id' => 'required|max:255',
            'org_defined_format_id' => 'required|max:255',
            'status' => 'required',
            'publication_date' => 'required|date',
            'dataset_type' => [
                'required',
                Rule::in(['Occurrence', 'Taxon']),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        if (isset($info['begin_or_single_date']) && isset($info['end_date']) && strtotime($info['begin_or_single_date']) > strtotime($info['end_date'])) {
            return response()->json([
                'message' => __('datetime'),
            ], 200, []);
        }
        if (empty($info['created_at'])) {
            $info['created_at'] = Carbon::now();
        }
        DB::beginTransaction();
        try {
            if ($info['quan_huyen']) {
                $info['quan_huyen'] = json_encode($info['quan_huyen']);
            }
            $dataset = DatasetResource::query()
                ->create($info);

            // if($info['protected_area_id']){
            //     DatasetResourceArea::create([
            //         'dataset_resource_id' => $dataset->id,
            //         'protected_area_id' => $info['protected_area_id']
            //     ]);
            // }
            $user = Auth::user();
            createLog($user->id,'add_dataset',$request->ip(),$request->header('User-Agent'),'Thêm mới bộ dữ liệu');
            DB::commit();
            return response()->json([
                'message' => __('message.success'),
                'result' => $dataset->id
            ], 200, []);
        } catch (Exception $exception) {
            DB::rollback();
            dd($exception);
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataset = DatasetResource::with('datasetResourceArea', 'resource')->find($id);
        return view('admin.category.dataset.update', [
            'dataset' => $dataset,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $info = $request->all();
        $dataset = DatasetResource::query()->findOrFail($id);
        $validator = Validator::make($info, [
            'title' => 'required|max:255|unique_with:dataset_resources,title,dataset_type,' . $id,
            'language' => 'required|max:255',
            'organization_id' => 'required|max:255',
            'org_defined_format_id' => 'required|max:255',
            'publication_date' => 'required|date',
            'dataset_type' => [
                'required',
                Rule::in(['Occurrence', 'Taxon']),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        if (isset($info['begin_or_single_date']) && isset($info['end_date']) && strtotime($info['begin_or_single_date']) > strtotime($info['end_date'])) {
            return response()->json([
                'message' => __('datetime'),
            ], 200, []);
        }
        if (empty($info['updated_at'])) {
            $info['updated_at'] = Carbon::now();
        }
        DB::beginTransaction();
        try {
            if ($info['quan_huyen']) {
                $info['quan_huyen'] = json_encode($info['quan_huyen']);
            } else {
                $info['quan_huyen'] = null;
            }
            $dataset->update($info);
            $user = Auth::user();
            createLog($user->id,'update_dataset',$request->ip(),$request->header('User-Agent'),'Cập nhật bộ dữ liệu '.$info['title']);
            DB::commit();
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $dataset = DatasetResource::find($id);

        DB::beginTransaction();
        try {
            $dataset->delete();
            $user = Auth::user();
            createLog($user->id,'delete_dataset',$request->ip(),$request->header('User-Agent'),'Xóa bộ dữ liệu '.$dataset['title']);
            DB::commit();
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (\Exception $ex) {
            \Log::error($ex);
            DB::rollback();
            dd($ex);
            return response()->json([
                'message' => __('message.fails'),
            ], 200, []);
        }
    }

    public function getOrgDefinedFormat()
    {
        $orgDefinedFormat =  OrgDefinedFormat::query()->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $orgDefinedFormat
        ], 200, []);
    }

    public function getOrganization()
    {
        $organization = Organization::query()->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $organization
        ], 200, []);
    }

    public function getProtectedArea()
    {
        $data = ProtectedArea::query()->get();
        return response()->json([
            'message' => __('message.success'),
            'result' => $data
        ], 200, []);
    }

    public function detailDatasetTaxon($id)
    {
        return view('admin.category.dataset.detailtaxon', [
            'id' => $id
        ]);
    }

    public function detailDatasetOccurrences($id)
    {
        return view('admin.category.dataset.detailoccurrences', [
            'id' => $id
        ]);
    }

    public function getDatasetDetailTaxon(Request $request, $id)
    {
        $page = $request->get('page', 1);
        $query = \App\DarwinCoreTaxon::where('dataset_resource_id', $id)->with('kingDom', 'phylum', 'class', 'order', 'family', 'genus', 'species', 'subSpecies', 'nbdsTaxonExtensionFirst')
            ->orderBy('created_at', 'desc');
        $data = $query->paginate(50, ['*'], 'page', $page);
        return response()->json([
            'message' => __('message.success'),
            'result' => $data
        ], 200, []);
    }

    public function getDatasetDetailOccurrences(Request $request, $id)
    {
        $page = $request->get('page', 1);
        $query = \App\DarwinCoreOccurrences::where('dataset_resource_id', $id)
            ->with(
                'darwinCoreTaxon',
                'darwinCoreTaxon.kingDom:id,name',
                'darwinCoreTaxon.phylum:id,name',
                'darwinCoreTaxon.class:id,name',
                'darwinCoreTaxon.order:id,name',
                'darwinCoreTaxon.family:id,name',
                'darwinCoreTaxon.genus:id,name',
                'region:id,name_vietnamese',
                'province:id,name_vietnamese',
                'district:id,name_vietnamese',
                'protectedArea:id,name'
            )
            ->orderBy('created_at', 'desc');
        $data = $query->paginate(50, ['*'], 'page', $page);

        return response()->json([
            'message' => __('message.success'),
            'result' => $data
        ], 200, []);
    }

    public function downloadTemplateTaxon()
    {
        if (file_exists(public_path() . '/import/darwin_core_taxon.xlsx')) {
            $excelFile = public_path() . '/import/darwin_core_taxon.xlsx';
            return response()->download($excelFile);
        } else {
            return  response()->json([
                'message' => __('system.file_not_found')
            ], 404, []);
        }
    }

    public function downloadTemplateOccurrences()
    {
        if (file_exists(public_path() . '/import/darwin_core_occurrences.xlsx')) {
            $excelFile = public_path() . '/import/darwin_core_occurrences.xlsx';
            return response()->download($excelFile);
        } else {
            return  response()->json([
                'message' => __('system.file_not_found')
            ], 404, []);
        }
    }

    public function uploadTaxon(Request $request, $id)
    {
        set_time_limit(0);
        $info = $request->all();
        $validator = Validator::make($info, [
            'file' => 'required|file|max:32768',      // max 32MB = 32768KB,
        ]);

        if ($validator->fails()) {
            $message = "validation failed";
            $failedRules = $validator->failed();

            if (isset($failedRules['file']['required'])) {
                $message = 'Tệp không được tìm thấy';
            } else if (isset($failedRules['file']['file'])) {
                $message = 'Không hỗ trợ định dạng tệp';
            } else if (isset($failedRules['file']['max'])) {
                $message = 'Kích thước tệp quá lớn';
            }

            return response()->json([
                'message' => $message,
                'data' => [
                    $validator->errors()->all()
                ]
            ], 400);
        }

        $file = $info['file'];
        $fileName = time() . '_' . $file->getClientOriginalName();
        $dataset_resource = DatasetResource::find($id);
        // set_time_limit(0);
        DB::beginTransaction();
        try {
            $file->storeAs('public/imports/', $fileName);
            $count = 2;
            $kingdoms = \App\KingDom::select('id', 'resource_id', DB::raw('trim(lower(name)) as lower_name'))->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->get();
            $phylums = \App\PhyLum::select('id', 'resource_id', 'kingdom_id', DB::raw('trim(lower(name)) as lower_name'))->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->get();
            $classes = \App\Classes::select('id', 'resource_id', 'phylum_id', DB::raw('trim(lower(name)) as lower_name'))->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->get();
            $orders = \App\Order::select('id', 'resource_id', 'class_id', DB::raw('trim(lower(name)) as lower_name'))->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->get();
            $families = \App\Family::select('id', 'resource_id', 'order_id', DB::raw('trim(lower(name)) as lower_name'))->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->get();
            $genus = \App\Genus::select('id', 'resource_id', 'family_id', DB::raw('trim(lower(name)) as lower_name'))->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->get();
            $species = \App\Species::select('id', 'resource_id', 'genus_id', DB::raw('trim(lower(name)) as lower_name'))->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->get();
            $subspecies = \App\SubSpecie::select('id', 'resource_id', 'species_id', DB::raw('trim(lower(name)) as lower_name'))->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->get();
            $arrayLoi = [];

            if (Storage::exists('public/imports/' . $fileName)) {
                \Excel::selectSheetsByIndex(0)->load(storage_path('app/public/imports/' . $fileName), function ($reader) use (&$arrayLoi, $id, &$count, $kingdoms, $phylums, $classes, $orders, $families, $genus, $species, $subspecies, $dataset_resource) {
                    $reader->each(function ($row) use (&$arrayLoi, $id, &$count, $kingdoms, $phylums, $classes, $orders, $families, $genus, $species, $subspecies, $dataset_resource) {
                        $info = $row->all();
                        $array = [];
                        if (isset($info['ten_khoa_hoc'])) {
                            $info['ten_khoa_hoc'] = trim($this->getOriginName(trim($info['ten_khoa_hoc'])));
                            $tenKhoaHocArray =  explode(" ", $info['ten_khoa_hoc']);
                            $tenKhoaHoc = null;
                            if (count($tenKhoaHocArray) > 1) {
                                $tenKhoaHoc = $tenKhoaHocArray[0] . ' ' . $tenKhoaHocArray[1];
                                $tenKhoaHoc = mb_strtolower($tenKhoaHoc);
                            }
                            $check = DarwinCoreTaxon::where(
                                'scientific_name',
                                'ilike',
                                "$tenKhoaHoc%"
                            )
                                ->where('dataset_resource_id', $id)->first();
                            if (empty($check) && $tenKhoaHoc != null) {
                                if (isset($info['ten_cong_bo_nam'])) {
                                    if (!is_numeric($info['ten_cong_bo_nam'])) {
                                        $info['ten_cong_bo_nam'] = null;
                                        $array['dong'] = $count;
                                        $array['loi'][] = 'Cột Tên công bố năm không đúng định dạng';
                                    }
                                }

                                if (isset($info['cap_do_don_vi_phan_loai']) || isset($info['phan_loai'])) {
                                    if (empty($info['cap_do_don_vi_phan_loai'])) {
                                        $info['cap_do_don_vi_phan_loai'] = $info['phan_loai'];
                                    }
                                    $info['cap_do_don_vi_phan_loai'] = mb_strtolower(trim($info['cap_do_don_vi_phan_loai']));
                                    switch ($info['cap_do_don_vi_phan_loai']) {
                                        case 'species':
                                            if (empty($info['chigiong'])) {
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Chi/Giống không được bỏ trống';
                                                break;
                                            }
                                            if (empty($info['gioi'])) {
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Giới không được bỏ trống';
                                                break;
                                            }
                                            $info['chigiong'] = trim($this->getOriginName(trim($info['chigiong'])));
                                            $info['gioi'] = trim($this->getOriginName(trim($info['gioi'])));
                                            $kingdomFile = $this->querySpecies($kingdoms, $info['gioi'], $dataset_resource);
                                            if (empty($kingdomFile)) {
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Giới không tồn tại trên hệ thống';
                                                break;
                                            }
                                            $gen = $this->queryGenus($genus, $info['chigiong'], $dataset_resource, $kingdomFile->id);
                                            if (empty($gen)) {
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Chi không tồn tại trên hệ thống';
                                                break;
                                            }
                                            $family = $this->queryTaxon($families, $gen->family_id, $dataset_resource);
                                            $order = $this->queryTaxon($orders, $family->order_id, $dataset_resource);
                                            $class = $this->queryTaxon($classes, $order->class_id, $dataset_resource);
                                            $phylum = $this->queryTaxon($phylums, $class->phylum_id, $dataset_resource);
                                            $kingdom = $this->queryTaxon($kingdoms, $phylum->kingdom_id, $dataset_resource);

                                            if ($kingdom->id != $kingdomFile->id) {
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Chi không khớp với Giới';
                                                break;
                                            }
                                            $this->createTaxon($info, $kingdom, $phylum, $class, $order, $family, $gen, $dataset_resource, null, $id, 'App\Species');
                                            break;
                                        case 'subspecies':
                                            if (empty($info['loai']) || !$info['loai']) {
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Loài không được bỏ trống với các bậc dưới loài';
                                            }
                                            $name = mb_strtolower(trim($this->getOriginName($info['loai'])));
                                            $spec = \App\Species::select('id', 'resource_id', 'genus_id', 'name')->where(function ($query) use ($dataset_resource) {
                                                $query->whereNull('resource_id');
                                                $query->orWhere('resource_id', $dataset_resource->resource_id);
                                            })->where('name', 'ilike', "$name%")->first();
                                            if (empty($spec)) {
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Loài không chính xác';
                                                break;
                                            }
                                            $gen = $this->queryTaxon($genus, $spec->genus_id, $dataset_resource);
                                            $family = $this->queryTaxon($families, $gen->family_id, $dataset_resource);
                                            $order = $this->queryTaxon($orders, $family->order_id, $dataset_resource);
                                            $class = $this->queryTaxon($classes, $order->class_id, $dataset_resource);
                                            $phylum = $this->queryTaxon($phylums, $class->phylum_id, $dataset_resource);
                                            $kingdom = $this->queryTaxon($kingdoms, $phylum->kingdom_id, $dataset_resource);
                                            $this->createTaxon($info, $kingdom, $phylum, $class, $order, $family, $gen, $dataset_resource, $spec, $id, 'App\SubSpecie');
                                            break;
                                        default:
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột cấp độ phân loài sai';
                                            break;
                                    }
                                } else {
                                    $array['dong'] = $count;
                                    $array['loi'][] = 'Cột Cấp độ phân loại loài để trống';
                                }
                            } else {
                                $array['dong'] = $count;
                                $array['loi'] = $tenKhoaHoc == null ? 'Cột tên khoa học không hợp lệ' : 'Cột tên khoa học đã tồn tại';
                                $array['ten_khoa_hoc'] =  $info['ten_khoa_hoc'];
                            }
                        } else {
                            if (isset($info['ten_cong_bo_nam']) && isset($info['gioi']) && isset($info['nganh']) && isset($info['lop']) && isset($info['bo']) && isset($info['ho']) && isset($info['chigiong']) && isset($info['loai'])) {
                                $array['dong'] = $count;
                                $array['loi'] = 'Cột tên khoa học trống';
                            }
                        }
                        if (!empty($array)) {
                            $array = array_merge($array, $info);
                            $arrayLoi[] = $array;
                        }
                        $count++;
                    });
                });

                if (file_exists(public_path() . '/import/import_loi.xlsx')) {
                    $excelFile = public_path() . '/import/import_loi.xlsx';
                    DB::commit();
                    return response()->json([
                        'message' => __('message.success'),
                        'result' => $arrayLoi,
                    ], 200, []);
                }
            }
            return response()->json([
                'message' => 'File not found'
            ], 200, []);
        } catch (Exception $exception) {
            DB::rollback();
            dd($exception);
            Log::error($exception);
            if (Storage::exists($fileName)) {
                Storage::delete($fileName);
            }

            return response()->json([
                'message' => __('message.fails')
            ], 500, []);
        }
    }
    private function queryGenus($query, $info, $dataset_resource, $kingdomId)
    {
        $name = mb_strtolower(trim($this->getOriginName($info)));
        $queryData = $query->where('lower_name', 'ilike', $name);
        $genId = null;
        if($queryData->count() > 1){
            foreach($queryData->toArray() as $item){
                $family = Family::where('id', $item['family_id'])->first();
                $order = Order::where('id', $family->order_id)->first();
                $class = Classes::where('id', $order->class_id)->first();
                $phylum = PhyLum::where('id', $class->phylum_id)->first();
                $kingdom = KingDom::where('id', $phylum->kingdom_id)->first();
                if($kingdomId == $kingdom->id){
                    $genId = $item['id'];
                }
            }
        }
        if($genId){
            $queryData = $queryData->where('id', $genId);
        }  
        $clone_query = clone $queryData;
        if (!empty($dataset_resource->resource_id)) {
            $queryClone = $clone_query->where('resource_id', $dataset_resource->resource_id)->first();
            if (!empty($queryClone)) {
                return $queryClone;
            }
        }
        return $queryData->first();
    }

    public function uploadOccurrences(Request $request, $id)
    {
        $information = $request->all();

        $validator = Validator::make($information, [
            'file' => 'required|file|max:32768',      // max 32MB = 32768KB,
        ]);

        if ($validator->fails()) {
            $message = "validation failed";
            $failedRules = $validator->failed();

            if (isset($failedRules['file']['required'])) {
                $message = 'Tệp không được tìm thấy';
            } else if (isset($failedRules['file']['file'])) {
                $message = 'Không hỗ trợ định dạng tệp';
            } else if (isset($failedRules['file']['max'])) {
                $message = 'Kích thước tệp quá lớn';
            }

            return response()->json([
                'message' => $message,
                'data' => [
                    $validator->errors()->all()
                ]
            ], 400);
        }

        $file = $information['file'];
        $fileName = time() . '_' . $file->getClientOriginalName();
        $organization_id = DatasetResource::find($id)->organization_id;
        DB::beginTransaction();
        try {
            $file->storeAs('public/imports/', $fileName);
            $count = 2;
            $arrayLoi = [];
            if (Storage::exists('public/imports/' . $fileName)) {
                \Excel::selectSheetsByIndex(0)->load(storage_path('app/public/imports/' . $fileName), function ($reader) use (&$arrayLoi, $id, &$count, $organization_id) {
                    $reader->each(function ($row) use (&$arrayLoi, $id, &$count, $organization_id) {
                        $info = $row->all();
                        $array = [];
                        if (isset($info['ten_khoa_hoc'])) {
                            $taxon = DarwinCoreTaxon::where(DB::raw('lower(scientific_name)'), mb_strtolower(trim($info['ten_khoa_hoc'])))->first();
                            if (empty($taxon)) {
                                $array['dong'] = $count;
                                $array['loi'][] = 'Cột tên khoa học chưa tồn tại trong bảng Taxon';
                            } else {
                                if (isset($info['co_so_cua_ban_ghi'])) {
                                    if (isset($info['ngay_dien_ra_su_kien'])) {
                                        try {
                                            if ($info['ngay_dien_ra_su_kien'] instanceof Carbon) {
                                                $date_identified = $info['ngay_dien_ra_su_kien'];
                                            } elseif (is_numeric($info['ngay_dien_ra_su_kien']))
                                                $event_date = Carbon::createFromTimestamp((int) ($info['ngay_dien_ra_su_kien'] - 25569) * 86400)->startOfDay();
                                            elseif (is_string($info['ngay_dien_ra_su_kien'])) {
                                                $event_date = Carbon::createFromFormat('Y-m-d', preg_replace('!\s+!', '', trim($info['ngay_dien_ra_su_kien'])))->startOfDay();
                                            } else {
                                                $event_date = null;
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Ngày diễn ra sự kiện không đúng định dạng ngày yyyy-mm-dd';
                                            }
                                        } catch (\Exception $ex) {
                                            $event_date = null;
                                            Log::error('dong: ' . $count);
                                            Log::error($ex);
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Ngày diễn ra sự kiện không đúng định dạng ngày yyyy-mm-dd';
                                        }
                                    } else $event_date = null;

                                    if (isset($info['tham_chieu_dia_ly_ngay'])) {
                                        try {
                                            if ($info['tham_chieu_dia_ly_ngay'] instanceof Carbon) {
                                                $date_identified = $info['tham_chieu_dia_ly_ngay'];
                                            } elseif (is_numeric($info['tham_chieu_dia_ly_ngay']))
                                                $georeferenced_date = Carbon::createFromTimestamp((int) ($info['tham_chieu_dia_ly_ngay'] - 25569) * 86400)->startOfDay();
                                            elseif (is_string($info['tham_chieu_dia_ly_ngay'])) {
                                                $georeferenced_date = Carbon::createFromFormat('Y-m-d', preg_replace('!\s+!', '', trim($info['ngay_dien_ra_su_kien'])))->startOfDay();
                                            } else {
                                                $georeferenced_date = null;
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Tham chiếu địa lý ngày không đúng định dạng ngày yyyy-mm-dd';
                                            }
                                        } catch (\Exception $ex) {
                                            $georeferenced_date = null;
                                            Log::error('dong: ' . $count);
                                            Log::error($ex);
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Tham chiếu địa lý ngày không đúng định dạng ngày yyyy-mm-dd';
                                        }
                                    } else $georeferenced_date = null;

                                    if (isset($info['phan_loai_ngay'])) {
                                        try {
                                            if ($info['phan_loai_ngay'] instanceof Carbon) {
                                                $date_identified = $info['phan_loai_ngay'];
                                            } elseif (is_numeric($info['phan_loai_ngay'])) {
                                                $date_identified = Carbon::createFromTimestamp((int) ($info['phan_loai_ngay'] - 25569) * 86400)->startOfDay();
                                            } elseif (is_string($info['phan_loai_ngay'])) {
                                                $date_identified = Carbon::createFromFormat('Y-m-d', preg_replace('!\s+!', '', trim($info['phan_loai_ngay'])))->startOfDay();
                                            } else {
                                                $date_identified = null;
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Phân loại ngày không đúng định dạng ngày yyyy-mm-dd';
                                            }
                                        } catch (\Exception $ex) {
                                            $date_identified = null;
                                            Log::error('dong: ' . $count);
                                            Log::error($ex);
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Phân loại ngày không đúng định dạng ngày yyyy-mm-dd';
                                        }
                                    } else $date_identified = null;

                                    if (isset($info['do_cao_toi_thieu_m'])) {
                                        if (!is_numeric($info['do_cao_toi_thieu_m'])) {
                                            $info['do_cao_toi_thieu_m'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Độ cao tối thiểu không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['do_cao_toi_da_m'])) {
                                        if (!is_numeric($info['do_cao_toi_da_m'])) {
                                            $info['do_cao_toi_da_m'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Độ cao tối đa không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['do_sau_toi_thieu_m'])) {
                                        if (!is_numeric($info['do_sau_toi_thieu_m'])) {
                                            $info['do_sau_toi_thieu_m'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Độ sâu tối thiểu không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['do_sau_toi_da_m'])) {
                                        if (!is_numeric($info['do_sau_toi_da_m'])) {
                                            $info['do_sau_toi_da_m'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Độ sâu tối đa không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['khoang_cach_toi_thieu_tren_be_mat_m'])) {
                                        if (!is_numeric($info['khoang_cach_toi_thieu_tren_be_mat_m'])) {
                                            $info['khoang_cach_toi_thieu_tren_be_mat_m'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Khoảng cách tối thiểu trên bề mặt không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['khoang_cach_toi_da_tren_be_mat_m'])) {
                                        if (!is_numeric($info['khoang_cach_toi_da_tren_be_mat_m'])) {
                                            $info['khoang_cach_toi_da_tren_be_mat_m'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Khoảng cách tối đa trên bề mặt không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['vi_do'])) {
                                        if (!is_numeric($info['vi_do'])) {
                                            $info['vi_do'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Vĩ độ không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['kinh_do'])) {
                                        if (!is_numeric($info['kinh_do'])) {
                                            $info['kinh_do'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Kinh độ không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['toa_do_khong_co_dinh'])) {
                                        if (!is_numeric($info['toa_do_khong_co_dinh'])) {
                                            $info['toa_do_khong_co_dinh'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Tọa độ không cố định không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['muc_chinh_xac_ve_toa_do'])) {
                                        if (!is_numeric($info['muc_chinh_xac_ve_toa_do'])) {
                                            $info['muc_chinh_xac_ve_toa_do'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Mức chính xác về tọa độ không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['ban_kinh_diem_dieu_chinh_khong_gian'])) {
                                        if (!is_numeric($info['ban_kinh_diem_dieu_chinh_khong_gian'])) {
                                            $info['ban_kinh_diem_dieu_chinh_khong_gian'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Bán kính điều chỉnh không gian không đúng định dạng số';
                                        }
                                    }

                                    if (isset($info['dau_chan_dieu_chinh_khong_gian'])) {
                                        if (!is_numeric($info['dau_chan_dieu_chinh_khong_gian'])) {
                                            $info['dau_chan_dieu_chinh_khong_gian'] = null;
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Dấu chân điều chỉnh không gian không đúng định dạng số';
                                        }
                                    }

                                    $region = null;
                                    $province = null;
                                    $district = null;
                                    if (isset($info['tinh'])) {
                                        $province = Province::where(DB::raw('lower(name)'), trim($this->vietnamese_to_english($info['tinh'])))->first();
                                        if (!empty($province)) {
                                            $region = Region::find($province->region_id);
                                            if (isset($info['huyen'])) {
                                                $district = District::where(DB::raw('lower(name)'), trim($this->vietnamese_to_english($info['huyen'])))
                                                    ->where('province_id', $id)->first();
                                                if (empty($district)) {
                                                    $array['dong'] = $count;
                                                    $array['loi'][] = 'Cột Huyện không chính xác';
                                                }
                                            }
                                        } else {
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Tỉnh không chính xác';
                                        }
                                    } else {
                                        if (isset($info['vung'])) {
                                            $region = Region::where(DB::raw('lower(name_vietnamese)'), trim($this->vietnamese_to_english($info['vung'])))->first();
                                            if (empty($region)) {
                                                $array['dong'] = $count;
                                                $array['loi'][] = 'Cột Vùng không chính xác';
                                            }
                                        }

                                        if (isset($info['huyen'])) { //do cùng tên Huyện giữa các tỉnh
                                            $array['dong'] = $count;
                                            $array['loi'][] = 'Cột Tỉnh đang trống';
                                        }
                                    }

                                    if (isset($info['khu_bao_ton'])) {
                                        $protectedarea = ProtectedArea::where(function ($query) use ($info) {
                                            $query->orWhere(DB::raw('lower(name)'), $info['khu_bao_ton']);
                                            $query->orWhere(DB::raw('lower(orig_name)'), $info['khu_bao_ton']);
                                        })->first();
                                    } else $protectedarea = null;

                                    DarwinCoreOccurrences::create([
                                        'scientific_name' => $taxon->scientific_name,
                                        'references' => $info['tai_lieu_tham_khao'],
                                        'institution_code' => $info['ma_so_co_quan'],
                                        'owner_institution_code' => $info['ma_so_co_quan_nguoi_so_huu'],
                                        'basis_of_record' => $info['co_so_cua_ban_ghi'],
                                        'information_withheld' => $info['thong_tin_bi_tu_choi'],
                                        'data_generalizations' => $info['khai_quat_hoa_du_lieu'],
                                        'dynamic_properties' => $info['dac_tinh_dong_thai'],
                                        'catalog_number' => $info['so_danh_muc'],
                                        'occurrence_remarks' => $info['nhan_xet_ve_phan_bo'],
                                        'record_number' => $info['so_ban_ghi'],
                                        'recorded_by' => $info['ghi_nhan_boi'],
                                        'individual_count' => $info['so_dem_ca_the'],
                                        'sex' => $info['gioi_tinh'],
                                        'life_stage' => $info['giai_doan_vong_doi'],
                                        'reproductive_condition' => $info['dieu_kien_sinh_san'],
                                        'behavior' => $info['hanh_vi'],
                                        'establishment_means' => $info['phuong_tien_xuat_ban'],
                                        'occurrence_status' => $info['tinh_trang_phan_bo'],
                                        'preparations' => $info['su_chuan_bi'],
                                        'disposition' => $info['cach_bo_tri'],
                                        'other_catalog_numbers' => $info['cac_so_danh_muc_khac'],
                                        'previous_identifications' => $info['cac_lan_phan_loai_truoc_day'],
                                        'associated_media' => $info['cac_trung_gian_di_kem'],
                                        'associated_references' => $info['cac_tai_lieu_tham_khao_lien_quan'],
                                        'associated_occurrences' => $info['cac_diem_phan_bo_co_lien_quan'],
                                        'associated_sequences' => $info['cac_chuoi_lien_quan'],
                                        'associated_taxa' => $info['cac_nhom_phan_loai_lien_quan'],
                                        'sampling_protocol' => $info['thu_tuc_thu_mau'],
                                        'sampling_effort' => $info['no_luc_thu_mau'],
                                        'event_date' => $event_date,
                                        'event_time' => $info['thoi_gian_dien_ra_su_kien'],
                                        'year' => empty($event_date) ? null : $event_date->year,
                                        'month' => empty($event_date) ? null : $event_date->month,
                                        'day' => empty($event_date) ? null : $event_date->day,
                                        'habitat' => $info['sinh_canh'],
                                        'field_number' => $info['so_truong'],
                                        'field_notes' => $info['ghi_chu_ve_truong'],
                                        'event_remarks' => $info['nhan_xet_ve_su_kien'],
                                        'continent' => $info['luc_dia'],
                                        'water_body' => $info['thuy_vuc'],
                                        'island_group' => $info['nhom_dao'],
                                        'island' => $info['dao'],
                                        'country' => $info['quoc_gia'],
                                        'province_id' => empty($province) ? null : $province->id,
                                        'district_id' => empty($district) ? null : $district->id,
                                        'region_id' => empty($region) ? null : $region->id,
                                        'municipality' => $info['xa'],
                                        'protected_area_id' => empty($protectedarea) ? null : $protectedarea->id,
                                        'locality' => $info['vi_tri'],
                                        'verbatim_elevation' => $info['sao_y_do_cao'],
                                        'minimum_elevation_in_meters' => $info['do_cao_toi_thieu_m'],
                                        'maximum_elevation_in_meters' => $info['do_cao_toi_da_m'],
                                        'verbatim_depth' => $info['sao_y_do_sau'],
                                        'minimum_depth_in_meters' => $info['do_sau_toi_thieu_m'],
                                        'maximum_depth_in_meters' => $info['do_sau_toi_da_m'],
                                        'minimum_distance_above_surface_in_meters' => $info['khoang_cach_toi_thieu_tren_be_mat_m'],
                                        'maximum_distance_above_surface_in_meters' => $info['khoang_cach_toi_da_tren_be_mat_m'],
                                        'location_according_to' => $info['vi_tri_theo'],
                                        'location_remarks' => $info['nhan_xet_ve_vi_tri'],
                                        'decimal_latitude' => $info['vi_do'],
                                        'decimal_longitude' => $info['kinh_do'],
                                        'geodetic_datum' => $info['moc_trac_dac'],
                                        'coordinate_uncertainty_in_meters' => $info['toa_do_khong_co_dinh'],
                                        'coordinate_precision' => $info['muc_chinh_xac_ve_toa_do'],
                                        'point_radius_spatialfit' => $info['ban_kinh_diem_dieu_chinh_khong_gian'],
                                        'footprint_wkt' => $info['dau_chan_wkt'],
                                        'footprint_srs' => $info['dau_chan_srs'],
                                        'footprint_spatial_fit' => $info['dau_chan_dieu_chinh_khong_gian'],
                                        'georeferenced_by' => $info['tham_chieu_dia_ly_theo'],
                                        'georeferenced_date' => $georeferenced_date,
                                        'georeference_protocol' => $info['thu_tuc_tham_chieu_dia_ly'],
                                        'georeference_sources' => $info['nguon_tham_chieu_dia_ly'],
                                        'georeference_verification_status' => $info['tinh_trang_tham_dinh_tham_chieu_dia_ly'],
                                        'georeference_remarks' => $info['nhan_xet_tham_chieu_dia_ly'],
                                        'earliest_eon_or_lowest_eonothem' => $info['lien_dai_som_nhat_hoac_lien_gioi_thap_nhat'],
                                        'latest_eon_or_highest_eonothem' => $info['lien_dai_muon_nhat_hoac_lien_gioi_cao_nhat'],
                                        'earliest_era_or_lowest_erathem' => $info['dai_som_nhat_hoac_gioi_thap_nhat'],
                                        'latest_era_or_highest_erathem' => $info['dai_muon_nhat_hoac_gioi_cao_nhat'],
                                        'earliest_period_or_lowest_system' => $info['ky_som_nhat_hoac_he_thong_thap_nhat'],
                                        'latest_period_or_highest_system' => $info['ky_muon_nhat_hoac_he_thong_cao_nhat'],
                                        'earliest_epoch_or_lowest_series' => $info['ky_nguyen_som_nhat_hoac_loat_thap_nhat'],
                                        'latest_epoch_or_highest_series' => $info['ky_nguyen_muon_nhat_hoac_loat_cao_nhat'],
                                        'earliest_age_or_lowest_stage' => $info['tuoi_som_nhat_hoac_giai_doan_thap_nhat'],
                                        'latest_age_or_highest_stage' => $info['tuoi_muon_nhat_hoac_giai_doan_cao_nhat'],
                                        'lowest_biostratigraphic_zone' => $info['vung_phan_vi_dia_tang_sinh_hoc_thap_nhat'],
                                        'highest_biostratigraphic_zone' => $info['vung_phan_vi_dia_tang_sinh_hoc_cao_nhat'],
                                        'lithostratigraphic_terms' => $info['gioi_han_phan_vi_thach_dia_tang'],
                                        'group' => $info['nhom'],
                                        'formation' => $info['dinh_dang'],
                                        'member' => $info['thanh_vien'],
                                        'bed' => $info['nen'],
                                        'identified_by' => $info['phan_loai_boi'],
                                        'date_identified' => $date_identified,
                                        'identification_references' => $info['tai_lieu_tham_khao_phan_loai'],
                                        'identification_verification_status' => $info['tinh_trang_tham_dinh_phan_loai'],
                                        'identification_remarks' => $info['nhan_xet_phan_loai'],
                                        'identification_qualifier' => $info['du_tieu_chuan_phan_loai'],
                                        'type_status' => $info['tinh_trang_kieu'],
                                        'dataset_resource_id' => $id,
                                        'organization_id' => $organization_id,
                                        'darwin_core_taxon_id' => $taxon->id,
                                    ]);
                                } else {
                                    $array['dong'] = $count;
                                    $array['loi'] = 'Cột Cơ sở của bản ghi đang trống';
                                }
                            }
                        } else {
                            if (isset($info['co_so_cua_ban_ghi'])) {
                                $array['dong'] = $count;
                                $array['loi'] = 'Cột tên khoa học trống';
                            }
                        }
                        if (!empty($array)) {
                            $arrayLoi[] = $array;
                        }
                        $count++;
                    });
                });

                if (file_exists(public_path() . '/import/import_loi.xlsx')) {
                    $excelFile = public_path() . '/import/import_loi.xlsx';

                    DB::commit();
                    return response()->json([
                        'message' => __('message.success'),
                        'result' => $arrayLoi,
                    ], 200, []);
                }
            }
            return response()->json([
                'message' => 'File not found'
            ], 200, []);
        } catch (Exception $exception) {
            DB::rollback();
            Log::error($exception);
            if (Storage::exists($fileName)) {
                Storage::delete($fileName);
            }

            return response()->json([
                'message' => __('message.fails')
            ], 500, []);
        }
    }
    private function queryTaxon($query, $taxon_id, $dataset_resource)
    {
        $queryData = $query->where('id', $taxon_id);
        $clone_query = clone $queryData;
        if (!empty($dataset_resource->resource_id)) {
            $queryClone = $clone_query->where('resource_id', $dataset_resource->resource_id)->first();
            if (!empty($queryClone)) {
                return $queryClone;
            }
        }

        return $queryData->first();
    }

    function createTaxon($info, $kingdom, $phylum, $class, $order, $family, $gen, $dataset_resource, $species, $id, $model_name)
    {
        $info['ten_khoa_hoc'] = trim($this->getOriginName($info['ten_khoa_hoc']));
        $darwintaxon = DarwinCoreTaxon::create([
            'scientific_name' => $info['ten_khoa_hoc'],
            'scientific_name_style' => empty($info['kieu_ten_khoa_hoc']) ? null : empty($info['kieu_ten_khoa_hoc']),
            'name_according_to' => empty($info['ten_dua_theo']) ? null : $info['ten_dua_theo'],
            'name_published_in' => empty($info['ten_cong_bo_trong']) ? null : $info['ten_cong_bo_trong'],
            'name_published_in_year' => empty($info['ten_cong_bo_nam']) ? null : $info['ten_cong_bo_nam'],
            'higher_classification' => empty($info['phan_loai_cao_hon']) ? null : $info['phan_loai_cao_hon'],
            'kingdom' => $kingdom->name,
            'kingdom_id' => $kingdom->id,
            'phylum' => $phylum->name,
            'phylum_id' => $phylum->id,
            'class' => $class->name,
            'class_id' => $class->id,
            'order' => $order->name,
            'order_id' => $order->id,
            'family' => $family->name,
            'family_id' => $family->id,
            'genus' => $gen->name,
            'genus_id' => $gen->id,
            'species_id' => $species ? $species->id : null,
            'specific_epithet' => empty($info['tinh_ngu_rieng']) ? null : $info['tinh_ngu_rieng'],
            'infraspecific_epithet' => empty($info['tinh_ngu_cu_the']) ? null : $info['tinh_ngu_cu_the'],
            // 'taxon_rank' => empty($info['cap_do_don_vi_phan_loai']) ? null : $info['cap_do_don_vi_phan_loai'],
            'scientific_name_authorship' => empty($info['tac_gia_ten_khoa_hoc']) ? (empty($info['tac_gia']) ? null : $info['tac_gia']) : $info['tac_gia_ten_khoa_hoc'],
            'vernacular_name' => empty($info['ten_tieng_viet']) ? null : $info['ten_tieng_viet'],
            'vernacular_name_english' => empty($info['ten_thong_thuong_tieng_anh']) && empty($info['ten_tieng_anh']) ? null : (empty($info['ten_thong_thuong_tieng_anh']) ? $info['ten_tieng_anh'] : $info['ten_thong_thuong_tieng_anh']),
            'vernacular_name_others' => empty($info['ten_thong_thuong_bang_tieng_khac']) ? null : $info['ten_thong_thuong_bang_tieng_khac'],
            'nomenclatural_code' => empty($info['ma_danh_phap']) ? null : $info['ma_danh_phap'],
            'taxonomic_status' => empty($info['tinh_trang_phan_loai_hoc']) ? null : $info['tinh_trang_phan_loai_hoc'],
            'nomenclatural_status' => empty($info['tinh_trang_danh_phap']) ? null : $info['tinh_trang_danh_phap'],
            'taxon_remarks' => empty($info['nhan_xet_ve_don_vi_phan_loai']) ? null : $info['nhan_xet_ve_don_vi_phan_loai'],
            'references' => empty($info['tham_khao']) ? null : $info['tham_khao'],
            'dataset_resource_id' => $id,
            'resource_id' => $dataset_resource->resource_id,
            // 'taxon_id' => $info['id_loai']
        ]);

        if (!empty($info['tu_dong_nghia'])) {
            Synonym::create([
                'name' => $info['tu_dong_nghia'],
                'darwin_core_taxon_id' => $darwintaxon->id,
            ]);
        }
        $nbds_extension = NbdsTaxonExtension::where('darwin_core_taxon_id', $darwintaxon->id)->first();
        $column_nbds_extension = [
            'iucn_2012',
            'red_list',
            'provenance_in_vietnam',
            'provenance_in_local',
            'natural',
            'invasive_status',
            'environmentally_sensitive',
            'rich_and_rare',
            'cites',
            'other_vietnamese_law_to_protect_species',
            'use',
            'morphological_description',
            'habitat',
            'phan_bo_ha_noi',
            'local_endemism',
            'classification_of_behavioral_features',
            'note',
            'nghi_dinh',
            'phu_luc_nghi_dinh',
            'phan_hang_sach_do'
        ];
        $column_excel = [
            'iucn_2012',
            'sach_do_viet_nam_2007',
            'nguon_goc_o_viet_nam',
            'nguon_goc_o_dia_phuong',
            'tu_nhien',
            'xam_lan',
            'do_nhay_voi_sinh_thaimoi_truong',
            'su_phong_phuquy_hiem',
            'cites',
            'luat_viet_nam_ve_bao_ton_loai',
            'su_dung',
            'mo_ta_dac_tinh_sinh_hoc',
            'moi_truong_song',
            'phan_bo_ha_noi',
            'tinh_dac_huu',
            'phan_loai_cac_dac_tinh_ve_hanh_vi',
            'ghi_chu',
            'nghi_dinh',
            'phu_luc_nghi_dinh',
            'phan_hang_theo_sach_do'
        ];
        $data_nbds_extension['darwin_core_taxon_id'] = $darwintaxon->id;
        foreach ($column_nbds_extension as $key => $item) {
            $data_nbds_extension[$item] = empty($info[$column_excel[$key]]) ? null : $info[$column_excel[$key]];
        }

        if (empty($nbds_extension)) {
            NbdsTaxonExtension::create($data_nbds_extension);
        } else {
            $nbds_extension->update($data_nbds_extension);
        }

        $tenKhoaHoc = null;
        $tenKhoaHocArray =  explode(" ", trim($this->getOriginName($info['ten_khoa_hoc'])));
        if (count($tenKhoaHocArray) > 1) {
            $tenKhoaHoc = $tenKhoaHocArray[0] . ' ' . $tenKhoaHocArray[1];
            $tenKhoaHoc = mb_strtolower($tenKhoaHoc);
        }

        if ($model_name == 'App\Species') {
            $spec = Species::where(
                'name',
                'ilike',
                "{$tenKhoaHoc}%"
            )->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->first();

            if (empty($spec) || $tenKhoaHoc == null) {
                $spec = $model_name::create([
                    'name' => ucfirst(trim($info['ten_khoa_hoc'])),
                    'name_vietnamese' => $info['ten_tieng_viet'],
                    'genus_id' => $gen->id,
                    'status' => 'accepted',
                    'resource_id' => $dataset_resource->resource_id
                ]);
            }
            $darwintaxon->update(['species_id' => $spec->id]);
        }
        if ($model_name == 'App\SubSpecie') {
            $spec = SubSpecie::where(
                'name',
                'ilike',
                "$tenKhoaHoc%"
            )->where(function ($query) use ($dataset_resource) {
                $query->whereNull('resource_id');
                $query->orWhere('resource_id', $dataset_resource->resource_id);
            })->first();

            if (empty($spec) || $tenKhoaHoc == null) {
                $spec = $model_name::create([
                    'name' => ucfirst(trim($info['ten_khoa_hoc'])),
                    'name_vietnamese' => $info['ten_tieng_viet'],
                    'species_id' => $species->id,
                    'taxon_rank' => 'subspecies',
                    'resource_id' => $dataset_resource->resource_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            $darwintaxon->update(['sub_species_id' => $spec->id]);
        }
        // $spec = $species->where('lower_name', mb_strtolower($info['ten_khoa_hoc']))->first();
        // if (empty($spec)) {
        //     $spec = $model_name::create([
        //         'name' => ucfirst(trim($info['ten_khoa_hoc'])),
        //         'name_vietnamese' => $info['ten_tieng_viet'],
        //         'genus_id' => $gen->id,
        //         'status' => 'accepted',
        //         'resource_id' => $dataset_resource->resource_id
        //     ]);
        // }
        // $darwintaxon->update(['species_id' => $spec->id]);
    }
    private function getOriginName($name)
    {
        $name = json_encode($name);
        $text = str_replace("\\u00a0", ' ', $name);
        return trim(json_decode($text));
    }
    public function export(Request $request, $id)
    {
        $data_type = $request->get('dataset_type');
        return Excel::create('Filename', function ($excel) use ($id, $data_type) {

            $excel->sheet('dataset', function ($sheet) use ($id, $data_type) {
                $query = \App\DatasetResource::query();
                $query->with(
                    'organization:id,name,name_vietnamese,contacts,email,organization_type,url,address,tel,portal_url_element',
                    'datasetResourceArea.protectedArea:name,id,orig_name',
                    'datasetReferences:name,id,dataset_resource_id'
                );
                $query->withCount('darwinCoreOccurrences', 'darwinCoreTaxons', 'darwinCoreTaxons', 'datasetReferences');
                $dataset = $query->find($id)->toArray();
                // dd($dataset);
                $dataset = [
                    [$data_type == "Occurrence" ? "Danh sách bộ dữ liệu về phân bố" : "Danh sách bộ dữ liệu về phân loại học"], [
                        "Tên",
                        "Ngày công bố",
                        "Công bố bởi",
                        "Số bộ phát hiện loài",
                        "Số đơn vị phân loại",
                        "Số tài liệu tham khảo cho bộ dữ liệu",
                        "Tóm lược",
                        "Mô tả địa lý",
                        "Độ phủ về phân loại",
                        "Thông tin thêm",
                        "Trích dẫn",
                        "Phân bố",
                    ],
                    [
                        $dataset["title"],
                        $dataset["publication_date"],
                        $dataset["organization"]["name"],
                        $dataset["darwin_core_occurrences_count"],
                        $dataset["darwin_core_taxons_count"],
                        $dataset["dataset_references_count"],
                        $dataset["abstract"],
                        $dataset["geographic_description"],
                        $dataset["taxonomic_coverage"],
                        $dataset["additional_info"],
                        $dataset["citation"],
                        $dataset["distribution"],
                    ]
                ];
                $sheet->fromArray($dataset, null, 'A1', false, false);
                $sheet->mergeCells('A1:L1');
                $sheet->setAutoSize(true);
                $sheet->cell('A2:L2', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('A1', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setFontWeight('bold');
                });
            });
            $excel->sheet('species', function ($sheet) use ($id) {
                $query = \App\DarwinCoreTaxon::query();
                $query->whereHas('dataResource', function ($query) {
                    $query->filterStatusPublic();
                });
                $query->where('dataset_resource_id', $id);
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
                    'genus_id', 'species_id', 'dataset_resource_id'
                ]);
                $data = $query->orderBy('scientific_name')->get()->toArray();
                $data = array_merge([
                    ["Danh sách đơn vị phân loại"], [
                        "Tên khoa học",
                        "Giới",
                        "Ngành",
                        "Lớp",
                        "Bộ",
                        "Họ",
                        "Chi",
                        "Loài",
                    ]
                ], array_map(function ($arr) {
                    return [
                        $arr["scientific_name"],
                        $this->checkEmpty($arr["king_dom"], "name"),
                        $this->checkEmpty($arr["phylum"], "name"),
                        $this->checkEmpty($arr["class"], "name"),
                        $this->checkEmpty($arr["order"], "name"),
                        $this->checkEmpty($arr["family"], "name"),
                        $this->checkEmpty($arr["genus"], "name"),
                        !empty($arr["species"]) ? (!empty($arr["species"]["name_vietnamese"]) ? $arr["species"]["name"] . "(" . $arr["species"]["name_vietnamese"] . ")" : $arr["species"]["name"]) : "",
                    ];
                }, $data));
                $sheet->fromArray($data, null, 'A1', false, false);
                $sheet->mergeCells('A1:H1');
                $sheet->setAutoSize(true);
                $sheet->cell('A2:H2', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('A1', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setFontWeight('bold');
                });
            });
        })->export('xls');
    }
    private function checkEmpty($value, $key)
    {
        return !empty($value) ? $value[$key] : "";
    }

    private function querySpecies($query, $info, $dataset_resource)
    {
        $name = mb_strtolower(trim($this->getOriginName($info)));
        $queryData = $query->where('lower_name', 'ilike', $name);
        $clone_query = clone $queryData;
        if (!empty($dataset_resource->resource_id)) {
            $queryClone = $clone_query->where('resource_id', $dataset_resource->resource_id)->first();
            if (!empty($queryClone)) {
                return $queryClone;
            }
        }
        return $queryData->first();
    }
    public function getDoiTuongBaoTon()
    {
        $khuBaoTon = ProtectedArea::select('name', 'id', 'orig_name', 'desig')->get();
        $coSoBaoTon = CoSoBaoTon::select('id', 'ten')->get();
        $hst = Lookup::where('group', 'hesinhthai')->select('id', 'name')->get();
        $data =
            [
                'khu_bao_ton' => $khuBaoTon,
                'co_so_bao_ton' => $coSoBaoTon,
                'he_sinh_thai' => $hst
            ];
        return response($data, 200);
    }
}
