<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name', 
        'description', 
        'code', 
        'system',
        'color'
    ];

    public function roleMenu(){
        return $this->belongsTo('App\RoleMenu,role_id,id');
    }

    public function users() {
        return $this->hasMany('App\User', 'role_id', 'id');            
    }

    public $timestamps = false;
}
