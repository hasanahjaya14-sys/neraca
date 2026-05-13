<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    public function metadata()
    {
        return $this->hasOne(Metadata::class);
    }
    protected $fillable = ['kategori_id', 'parent_id', 'name', 'urutan'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function parent()
    {
        return $this->belongsTo(SubKategori::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SubKategori::class, 'parent_id')->orderBy('urutan');
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }
}