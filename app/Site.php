<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';
    protected $fillable = ['type', 'name', 'area', 'protected_area_id', 'degree', 'regular_conservation_activities', 'results_of_conservation_activities', 'project_activities', 'project_outcomes', 'partners_for_bd_conservation_priority', 'tasks_in_bd_conservation', 'remarks', 'references', 'organization_id'];

    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organization_id', 'id')->withDefault();
    }

    public function protectedArea()
    {
        return $this->belongsTo('App\ProtectedArea', 'protected_area_id', 'id')->withDefault();
    }
}
