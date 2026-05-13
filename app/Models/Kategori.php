<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['kode', 'name', 'urutan'];

    public function subKategoris()
    {
        return $this->hasMany(SubKategori::class)->whereNull('parent_id')->orderBy('urutan');
    }
}