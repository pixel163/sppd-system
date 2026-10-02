<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use App\Models\KotaKategori;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    public function index()
    {
        $kotas = Kota::with('kategori')->latest()->paginate(10);
        
        return view('master.kota.index', compact('kotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kota,name',
        ]);

        Kota::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Kota berhasil ditambahkan!');
    }

    public function update(Request $request, Kota $kota)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kota,name,' . $kota->id,
        ]);

        $kota->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Kota berhasil diperbarui!');
    }

    public function destroy(Kota $kota)
    {
        $kota->delete();
        return redirect()->back()->with('success', 'Data Kota berhasil dihapus!');
    }
}