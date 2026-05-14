<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    protected $table = 'metadatas';

    protected $fillable = [
        'sub_kategori_id',
        'definisi',
        'sumber_data',
        'satuan',
        'metode_perhitungan',
        'rumus',
        'catatan'
    ];

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class);
    }
}