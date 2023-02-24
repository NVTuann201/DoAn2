<?php

namespace App\Traits;
use App\Traits\ExecuteRedisCache;
use App\Traits\GetDanhMuc;
use App\Traits\GetMenus;
use App\Traits\AttachMedia;

trait GetDataCache {
  use ExecuteRedisCache, GetDanhMuc, GetMenus, AttachMedia;
}