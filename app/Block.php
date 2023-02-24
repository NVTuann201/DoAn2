<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $table = 'blocks';
    protected $fillable = ['name','description','code','site_id'];
    public function site()
    {
        return $this->belongsTo('App\Site', 'site_id', 'id')->withDefault();
    }
}
