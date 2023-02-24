<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VnRedlistThreatStatus extends Model
{
    protected $table = 'vn_redlist_threat_statuses';
    protected $fillable = ['threat_status', 'year_evaluated', 'description', 'darwin_core_taxon_id'];

    public function DarwinCoreTaxon()
    {
        return $this->belongsTo('App\DarwinCoreTaxon', 'darwin_core_taxon_id', 'id')->withDefault();
    }
}
