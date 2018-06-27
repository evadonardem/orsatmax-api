<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponentsTotal extends Model
{
    protected $table = 'txo_total_components';
    protected $fillable = [];
    public $timestamps = false;

    public function txo() {
    	return $this->belongsTo(TXO::class, 'txo_dump_id', 'id');
    }
}
