<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';
    protected $fillable = [];
    public $timestamps = false;

    public function dumps() {
    	return $this->hasMany(TXO::class, 'site_id', 'id');
    }
}
