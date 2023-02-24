<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Media;
use App\ProtectedArea;
use App\Traits\GetDataCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Optix\Media\MediaUploader;

class ProtectedAreaController extends Controller
{
    use GetDataCache;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.category.protectedarea.index');
        }
    }

    public function getProtectedArea(Request $request)
    {
        $search = $request->get('search');
        $desigType = $request->get('desig_type');
        $subLocs = $request->get('sub_locs');
        $desigs = $request->get('desigs');
        $status = $request->get('status');
        $govType = $request->get('gov_type');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $query = \App\ProtectedArea::query();
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('orig_name', 'ilike', "%{$search}%");
            });
        }
        if (!empty($desigType)) {
            $query->where('desig_type', $desigType);
        }
        if (!empty($subLocs)) {
            $query->whereIn('sub_loc', $subLocs);
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
        $query->orderBy('name', 'asc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->paginate($perPage, ['*'], 'page', $page),
        ], 200, []);
    }

    public function create()
    {
        return view('admin.category.protectedarea.add');
    }

    public function add(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'name' => 'required|max:255|unique:protected_areas',
            'geometry' => 'nullable|json',
        ], [
            'name.unique' => 'Tên khu bảo tồn đã tồn tại'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        if (empty($info['wdpaid'])) {
            $info['wdpaid'] = 1;
        }
        if (empty($info['wdpa_pid'])) {
            $info['wdpa_pid'] = 1;
        }
        if (empty($info['country'])) {
            $info['country'] = "VN";
        }

        if (!$info['isInternational']) {
            $info['desig_eng'] = null;
        }

        try {
            if ($info['quan_huyen']) {
                $info['quan_huyen'] = json_encode($info['quan_huyen']);
            } else $info['quan_huyen'] = null;
            if ($info['files']) {
                $info['files'] = json_encode($info['files']);
            } else $info['files'] = null;
            $info['sub_loc'] = 'Hà Nội';
            ProtectedArea::query()
                ->create($info);
            $user = Auth::user();
            createLog($user->id, 'add_khu_bao_ton', $request->ip(), $request->header('User-Agent'), 'Thêm mới khu bảo tồn');

            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (\Exception $exception) {
            dd($exception);
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }
    }

    public function show($id)
    {
        $data = ProtectedArea::with('region', 'media')->findOrFail($id);
        $files = [];
        $fileIds = json_decode($data['files']);
        if ($data['files'] && count($fileIds) > 0) {
            $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
            $data['fileList'] = $files->toArray();
        } else $data['fileList'] = [];


        return view('admin.category.protectedarea.update', [
            'protectedarea' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $protectedarea = ProtectedArea::findOrFail($id);
        $info = $request->all();
        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'geometry' => 'nullable',
        ]);

        $trungLap = ProtectedArea::where('name', $info['name'])->where('id', '!=', $id)->first();
        if ($trungLap) {
            return response()->json([
                'message' => __('Tên khu bảo tồn đã tồn tại'),
            ], 200, []);
        }
        if (!$info['isInternational']) {
            $info['desig_eng'] = null;
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'data' => $validator->errors()
            ], 400, []);
        }
        try {
            DB::beginTransaction();
            if ($info['quan_huyen']) {
                $info['quan_huyen'] = json_encode($info['quan_huyen']);
            } else $info['quan_huyen'] = null;
            if ($info['files']) {
                $info['files'] = json_encode($info['files']);
            } else $info['files'] = null;
            $info['sub_loc'] = 'Hà Nội';
            $protectedarea->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_khu_bao_ton', $request->ip(), $request->header('User-Agent'), 'Cập nhật khu bảo tồn');

            DB::commit();
            return response()->json([
                'message' => 'message.success',
                'result' => [],
            ], 200, []);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json([
                'message' => __('system.update_error'),
                'result' => [],
            ], 500, []);
        }
    }

    public function delete(Request $request, $id)
    {
        $protectedarea = ProtectedArea::findOrFail($id);

        DB::beginTransaction();
        try {
            $protectedarea->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_khu_bao_ton', $request->ip(), $request->header('User-Agent'), 'Xóa khu bảo tồn');
            DB::commit();
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            return response()->json([
                'message' => __('system.update_error'),
            ], 500, []);
        }
    }
    public function uploadImage(Request $request, $id)
    {
        $query = \App\ProtectedArea::query();
        $data = $query->findOrFail($id);
        $file = $request->file('file');
        $media = [];
        if ($request->get('type') === 'image' && isset($file)) {
            $media = MediaUploader::fromFile($file)
                ->useFileName(strval(time()) . '_' . $file->getClientOriginalName())
                ->useName($request->get('file_name') ?? $file->getClientOriginalName())->withAttributes(['properties' => $request->except(['file', 'file_name'])])
                ->upload();
            $data->attachMedia($media);
        }
        if ($request->get('type') === 'youtube') {
            $media = new Media();
            $media->name = $request->get('file_name');
            $media->file_name = $request->get('file_name');
            $media->disk = 'youtube';
            $media->mime_type = 'link';
            $media->size = 0;
            $media->properties = $request->except(['file', 'file_name']);

            $media->save();
            $data->attachMedia($media);
        }
        return response()->json([
            'message' => __('message.success'),
            'result' => $media,
        ], 200, []);
    }
}
