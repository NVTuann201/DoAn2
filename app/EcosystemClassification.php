<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EcosystemClassification extends Model
{
    protected $table = 'ecosystem_classifications';
    protected $fillable = ['name','description','parent_id'];
}
