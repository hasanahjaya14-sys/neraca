<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['kode_bps', 'name', 'tipe', 'provinsi_id'];

    public function provinsi()
    {
        return $this->belongsTo(Region::class, 'provinsi_id');
    }

    public function kabkos()
    {
        return $this->hasMany(Region::class, 'provinsi_id');
    }
}