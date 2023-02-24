<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NguonGen extends Model
{
    protected $guarded = [];
    public function nhomGen(){
        return $this->belongsTo('App\NhomGen', 'nhom_gen_id', 'id');
    }
    public function loaiGen(){
        return $this->belongsTo('App\LoaiGen', 'loai_gen_id', 'id');
    }
    public function species(){
        return $this->belongsTo('App\Species', 'loai_id', 'id');
    }
    public function suDung(){
        return $this->belongsTo('App\Lookup', 'su_dung_id', 'id');
    }
    public function genQuyHiem(){
        return $this->belongsTo('App\Lookup', 'gen_quy_hiem_id', 'id');
    }
    public function tinhTrangLuuTru(){
        return $this->belongsTo('App\Lookup', 'tinh_trang_luu_giu_id', 'id');
    }
    public function tinhTrangNghienCuu(){
        return $this->belongsTo('App\Lookup', 'tinh_trang_nghien_cuu_id', 'id');
    }
    public function tinhTrangSuDung(){
        return $this->belongsTo('App\Lookup', 'tinh_trang_su_dung_id', 'id');
    }
    public function nguonGocVietNam(){
        return $this->belongsTo('App\Lookup', 'nguon_goc_viet_nam_id', 'id');
    }
    public function nguonGocDiaPhuong(){
        return $this->belongsTo('App\Lookup', 'nguon_goc_dia_phuong_id', 'id');
    }
    public function nguonGocCoSo(){
        return $this->belongsTo('App\Lookup', 'nguon_goc_co_so_id', 'id');
    }
    public function phuongThucLuuGiu(){
        return $this->belongsTo('App\Lookup', 'phuong_thuc_luu_giu_id', 'id');
    }
    public function tinhTrang(){
        return $this->belongsTo('App\Lookup', 'tinh_trang_id', 'id');
    }
    public function genCoDieuKien(){
        return $this->belongsTo('App\Lookup', 'gen_co_dieu_kien_id', 'id');
    }
    public function noiLuuGius(){
        return $this->belongsToMany('App\NoiLuuGiu', 'nguon_gen_noi_luu_gius', 'nguon_gen_id', 'noi_luu_giu_id');
    }
    public function noiLuuGiuBangTrungGian(){
        return $this->hasMany('App\NguonGenNoiLuuGiu', 'nguon_gen_id', 'id');
    }
}
