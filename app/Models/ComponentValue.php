<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponentValue extends Model
{
    protected $table = 'component_values';
    protected $fillable = [];
    public $timestamps = false;

    public function site() {
    	return $this->belongsTo(Site::class, 'site_id', 'id');
    }

    public function txo() {
    	return $this->belongsTo(TXO::class, 'txo_dump_id', 'id');
    }

    public function air() {
      return $this->belongsTo(Air::class, 'component_name', 'component_name');
    }
}
