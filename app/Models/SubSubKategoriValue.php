<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSubKategoriValue extends Model
{
    protected $table = 'sub_sub_kategori_values';

    protected $fillable = ['sub_sub_kategori_id', 'region_id', 'triwulan', 'tahun', 'value'];

    public function subSubKategori()
    {
        return $this->belongsTo(SubSubKategori::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}