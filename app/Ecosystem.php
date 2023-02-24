<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ecosystem extends Model
{
    protected $table = 'ecosystems';
    protected $fillable = ['type','name','area','references','ecosystem_classification_id','site_id','description'];
    public function ecosystemClassification() {
        return $this->belongsTo('App\EcosystemClassification', 'ecosystem_classification_id','id')->withDefault();
    }
    public function site() {
        return $this->belongsTo('App\Site', 'site_id','id')->withDefault();
    }
}
