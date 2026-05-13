<?php

namespace App\Http\Controllers;

use App\Models\Kategori;

class MetadataController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('urutan')->get();
        return view('metadata.index', compact('kategoris'));
    }

    public function show($id)
    {
        $kategori = Kategori::with([
            'subKategoris.children.metadata',
            'subKategoris.metadata',
        ])->findOrFail($id);

        return view('metadata.show', compact('kategori'));
    }
}