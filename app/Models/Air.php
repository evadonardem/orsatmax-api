<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Air extends Model
{
    protected $table = 'airs_list';
    protected $fillable = [
      'carbon_no',
      'alias',
      'aqi_no',
      'component_name',
      'cas'
    ];
    public $timestamps = false;
}
