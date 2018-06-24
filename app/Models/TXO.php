<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TXO extends Model
{
    protected $table = 'txo_dumps';
    protected $fillable = [];
    public $timestamps = false;

    public function site() {
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }

    public function componentValues() {
    	return $this->hasMany(ComponentValue::class, 'txo_dump_id', 'id');
    }

}
