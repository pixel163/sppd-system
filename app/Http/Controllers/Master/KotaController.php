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
        $kotas = Kota::with('kategori','area')->latest()->paginate(10);
        $kategoris = KotaKategori::where('is_active', true)->get();
        
        return view('master.kota.index', compact('kotas','kategoris'));
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

    public function update(Request $request, $id)
    {
        $kota = \App\Models\Kota::findOrFail($id);

        $request->validate([
            'kota_kategori_id' => 'required|exists:kota_kategori,id',
            'name'             => 'required|string|max:255|unique:kota,name,' . $kota->id,
        ]);

        $kota->update([
            'kota_kategori_id' => $request->kota_kategori_id,
            'name'             => $request->name,
            'is_active'        => $request->input('is_active') == '1' ? 1 : 0, 
        ]);

        return redirect()->back()->with('success', 'Data Kota berhasil diperbarui!');
    }

    // public function update(Request $request, $id)
    // {
    //     // Cari manual berdasarkan ID yang dikirim dari form
    //     $kota = \App\Models\Kota::findOrFail($id);

    //     $request->validate([
    //         'name' => 'required|string|max:255|unique:kota,name,' . $kota->id,
    //     ]);

    //     $kota->update([
    //         'name'      => $request->name,
    //         'is_active' => $request->input('is_active') == '1' ? 1 : 0, 
    //     ]);

    //     return redirect()->back()->with('success', 'Data Kota berhasil diperbarui!');
    // }

    public function destroy(Kota $kota)
    {
        $kota->delete();
        return redirect()->back()->with('success', 'Data Kota berhasil dihapus!');
    }
}