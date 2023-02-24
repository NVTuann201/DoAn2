<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'event_time', 'action', 'ip_address', 'content','noi_dung'
    ];

    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function user(){
        return $this->belongsTo('App\User')->withDefault();
    }
    
    protected static function boot()
    {
        parent::boot();       
    }

}
