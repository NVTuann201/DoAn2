<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table = 'families';
    protected $fillable = ['name', 'name_vietnamese', 'notes', 'order_id', 'status', 'synonym_id', 'resource_id'];
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }
    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }

}
