<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariableValue extends Model
{
    protected $fillable = ['sub_kategori_id', 'region_id', 'triwulan', 'tahun', 'value'];

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}