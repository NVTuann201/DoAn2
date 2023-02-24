<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait PermissionDataScope
{
    protected $isFieldTinhThanhIsJson = false;
    public function scopeOfPermission(Builder $query)
    {
        $user = Auth::user();
        if ($user->provinces()->count() > 0) {
            $province_ids = $user->provinces()->select('provinces.id')->get()->pluck('id');
            if ($this->isFieldTinhThanhIsJson) {
                $query->where(function ($query) use ($province_ids) {
                    foreach ($province_ids as $province_id) {
                        $query->orWhereRaw(DB::raw("tinh_thanh_id::jsonb @> '[" . $province_id . "]'"));
                    }
                });
            } else {
                $query->whereIn('tinh_thanh_id', $province_ids);
            }
        }
        return $query;
    }
}
