<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['name', 'name_vietnamese', 'notes', 'class_id', 'status', 'synonym_id', 'resource_id'];
    public $timestamps = false;

    function class ()
    {
        return $this->belongsTo('App\Classes', 'class_id');
    }
    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }

}
