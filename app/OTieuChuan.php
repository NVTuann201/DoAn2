<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class OTieuChuan extends Model
{
    use PostgisTrait;
    protected $guarded = [];
    // protected $fillable = [
    //     'ten',
    //     'khu_sinh_thai',
    //     'do_cao',
    //     'hinh_dang',
    //     'kich_thuoc',
    //     'dia_diem_id',
    //     'vi_tri',
    //     'geom'
    // ];
    protected $postgisFields = [
        'geom',
    ];
    public function diaDiem()
    {
        return $this->belongsTo('App\ProtectedArea', 'dia_diem_id', 'id');
    }
    // public static function boot()
    // {
    //     parent::boot();
    //     $fillable = [
    //         'ten',
    //         'khu_sinh_thai',
    //         'do_cao',
    //         'hinh_dang',
    //         'kich_thuoc',
    //         'dia_diem_id',
    //         'vi_tri',
    //     ];
    //     self::creating(function ($model) use ($fillable) {
    //         dd($model);
    //         foreach ($fillable as $col) {
    //             if (!empty($model->{$col})) {
    //                 $model->{$col} = trim($model->{$col});
    //             }
    //         }
    //     });
    //     self::updating(function ($model) use ($fillable) {
    //         foreach ($fillable as $col) {
    //             if (!empty($model->{$col})) {
    //                 $model->{$col} = trim($model->{$col});
    //             }
    //         }
    //     });
    // }
}
