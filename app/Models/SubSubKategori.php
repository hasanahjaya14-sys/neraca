<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSubKategori extends Model
{
    protected $table = 'sub_sub_kategoris';

    protected $fillable = ['sub_kategori_id', 'region_id', 'name', 'urutan'];

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function values()
    {
        return $this->hasMany(SubSubKategoriValue::class);
    }
}