<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    protected $fillable = ['region_id', 'subject_type', 'subject_id', 'formula_string'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function subject()
    {
        return match ($this->subject_type) {
            'kategori' => $this->belongsTo(Kategori::class, 'subject_id'),
            'sub_kategori' => $this->belongsTo(SubKategori::class, 'subject_id'),
        };
    }
}