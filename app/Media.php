<?php

namespace App;

use Optix\Media\Models\Media  as BaseMedia;

class Media extends BaseMedia
{
    protected $guarded = [];
    protected $casts = [
        'properties' => 'array',
    ];
    protected $appends = ['link', 'thumbnail'];
    protected $hidden = ['pivot'];
    public function getLinkAttribute()
    {
        return $this->getUrl();
    }
    public function getThumbnailAttribute()
    {
        return $this->getUrl('thumbnail');
    }
    public static function boot()
    {
        parent::boot();
        static::deleted(function ($model) {
            $model->filesystem()->deleteDirectory(
                $model->getDirectory()
            );
        });
    }
}
