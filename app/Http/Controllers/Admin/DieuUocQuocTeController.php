<?php

namespace App\Http\Controllers\admin;

use App\DieuUocQuocTe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\json_decode;

class DieuUocQuocTeController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.dieuuoc.index');
    }
    public function addDieuUoc(Request $request)
    {
        $info = $request->only('ten', 'mo_ta', 'ngay_ban_hanh', 'ngay_hieu_luc', 'ngay_viet_nam_tham_gia', 'so_quoc_gia_tham_gia', 'hieu_luc', 'files', 'noi_dung_chinh', 'noi_dung_toan_van', 'ghi_chu');
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            if (isset($info['files']) && is_array($info['files'])) {
                $info['files'] = json_encode($info['files']);
            } else {
                $info['files'] = null;
            }
            DieuUocQuocTe::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_dieu_uoc_quoc_te', $request->ip(), $request->header('User-Agent'), 'Thêm mới điều ước quốc tế ' .$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getDieuUocQuocTe(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nam_ban_hanh = $request->get('nam_ban_hanh', null);
        $nam_viet_nam_tham_gia = $request->get('nam_viet_nam_tham_gia', null);
        $nam_co_hieu_luc = $request->get('nam_co_hieu_luc', null);
        $hieu_luc = $request->get('hieu_luc', null);
        $query = DieuUocQuocTe::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_chinh', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_toan_van', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
            });
        }
        if($nam_ban_hanh != null){
            $query->whereYear('ngay_ban_hanh', $nam_ban_hanh);
        }
        if($nam_viet_nam_tham_gia != null){
            $query->whereYear('ngay_viet_nam_tham_gia', $nam_viet_nam_tham_gia);
        }
        if($nam_co_hieu_luc != null){
            $query->whereYear('ngay_co_hieu_luc', $nam_co_hieu_luc);
        }
        if($hieu_luc != null){
            $query->where('hieu_luc', $hieu_luc);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as $item) {
            $files = [];
            $fileIds = json_decode($item['files']);
            if ($item['files'] && count($fileIds) > 0) {
                $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
                $item['fileList'] = $files->toArray();
            }else $item['fileList'] = [];
        }
        return $data;
    }
    public function editDieuUoc(Request $request)
    {
        $info = $request->only('id','ten', 'mo_ta', 'ngay_ban_hanh', 'ngay_hieu_luc', 'ngay_viet_nam_tham_gia', 'so_quoc_gia_tham_gia', 'hieu_luc', 'files', 'noi_dung_chinh', 'noi_dung_toan_van', 'ghi_chu');
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            if (isset($info['files']) && is_array($info['files'])) {
                $info['files'] = json_encode($info['files']);
            } else {
                $info['files'] = null;
            }
            DieuUocQuocTe::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_dieu_uoc_quoc_te', $request->ip(), $request->header('User-Agent'), 'Cập nhật điều ước quốc tế ' .$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {
            $Files = $request->file('file');
            $file_id = [];
            $isTooBig = false;
            $maxSize = 50971520;
            DB::beginTransaction();
            foreach ($Files as $file) {
                try {
                    if ($file->getSize() > $maxSize) {
                        $isTooBig = true;
                    }
                    $name = $file->getClientOriginalName();
                    $hashName = rand(100000, 9999999) . time() . $name;
                    $file->storeAs('public/files/',  $hashName);
                    $taptin = Media::create([
                        'name' => $name,
                        'file_name' => $hashName,
                        'disk' => 'storage/files/' . $hashName,
                        'size' => $file->getSize(),
                        'mime_type' =>  $file->getMimeType()
                    ]);
                    $file_id[] =  $taptin->getOriginal();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['message' => 'File không hợp lệ'], 403);
                }
            }
            if ($isTooBig) {
                DB::rollBack();
                return response(['message' => 'File dung lượng tối đa 20 Mb'], 422);
            }
            DB::commit();
            return response()->json(['files' => $file_id, 'message' => 'Done'], 200);
        } else {
            return response()->json(['message' => 'File không tồn tại'], 404);
        }
    }

    public function xoaDieuUoc(Request $request, $id){
        try{
            $info=DieuUocQuocTe::where('id',$id)->first();
            DieuUocQuocTe::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_dieu_uoc_quoc_te', $request->ip(), $request->header('User-Agent'), 'Xóa điều ước quốc tế '.$info['ten']);
            return response(['message' => 'Done'], 200);
        }catch(\Exception $e){
            return response(['message' => 'Error'], 500);
        }
    }
    public function viewSearch(){
        return view('search.nolucbaoton.conguoc.conguoc', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function getDieuUocQuocTeSearch(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        $query = DieuUocQuocTe::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_chinh', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_toan_van', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as $item) {
            $files = [];
            $fileIds = json_decode($item['files']);
            if ($item['files'] && count($fileIds) > 0) {
                $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
                $item['fileList'] = $files->toArray();
            }else $item['fileList'] = [];
        }
        return $data;
    }
    public function chiTietDieuUoc($id){
        $data = DieuUocQuocTe::where('id',$id)->first();
        $files = [];
        $fileIds = json_decode($data['files']);
        if ($data['files'] && count($fileIds) > 0) {
            $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
            $data['fileList'] = $files->toArray();
        }else $data['fileList'] = [];
        return view('search.nolucbaoton.conguoc.detail', [
            'lang' => Session::get('locale'),
            'data' => json_encode($data)
        ]);
    }
}
