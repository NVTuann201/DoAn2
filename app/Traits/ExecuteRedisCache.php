<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

trait ExecuteRedisCache
{
    private function genKey($type, $key_of_type)
    {
        return config('app.key') . '-' . $type . '-' . $key_of_type;
    }

    public function put($type = 'default', $key_of_type, $value, $expires_at = 480)
    {
        return Cache::put($this->genKey($type, $key_of_type), $value, $expires_at);
    }

    public function get($type = 'default', $key_of_type)
    {
        return Cache::get($this->genKey($type, $key_of_type));
    }

    public function has($type = 'default', $key_of_type)
    {
        return Cache::has($this->genKey($type, $key_of_type));
    }

    public function forget($type = 'default', $key_of_type=null)
    {
        if (!empty($key_of_type)) {
            $key = $this->genKey($type, $key_of_type);
        }else {
            $key=$type;
        }
        if (Cache::has($key)) {
            return Cache::forget($key);
        }
    }

    public function forever($type = 'default', $key_of_type, $value)
    {
        return Cache::forever($this->genKey($type, $key_of_type), $value);
    }

    public function putTags($tags, $key, $value, $minutes = 480)
    {
        if (is_array($tags)) {
            Cache::tags($tags)->put($key, $value, $minutes);
        }
    }

    public function putTagsForever($tags, $key, $value)
    {
        if (is_array($tags)) {
            return Cache::tags($tags)->forever($key, $value);
        }
    }

    public function putTagForever($tag, $key, $value)
    {
        return Cache::tags([$tag])->forever($key, $value);
    }

    public function getTagedCacheItems($tags, $key)
    {
        if (is_array($tags)) {
            return Cache::tags($tags)->get($key);
        }
    }

    public function getTagedCacheItem($tag, $key)
    {
        return Cache::tags([$tag])->get($key);
    }

    public function flushTags($tags)
    {
        if (is_array($tags)) {
            return Cache::tags($tags)->flush();
        }
    }

    public function flushTag($tag)
    {
        return Cache::tags($tag)->flush();
    }

    public function flush()
    {
        return Cache::flush();
    }
}
