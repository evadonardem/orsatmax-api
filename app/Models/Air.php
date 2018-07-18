<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Air extends Model
{
    protected $table = 'airs_list';
    protected $fillable = [
      'component_name',
      'alias',
      'aqi_no',
      'carbon_no',
      'cas'
    ];
    public $timestamps = false;
}
