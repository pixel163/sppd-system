<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    
    public function index()
    {
        $golongans = Golongan::latest()->paginate(10);
        
        return view('master.golongan.index', compact('golongans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:golongan,name',
        ]);

        Golongan::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Golongan berhasil ditambahkan!');
    }

    public function update(Request $request, Golongan $golongan)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:golongan,name,' . $golongan->id,
        ]);

        $golongan->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Golongan berhasil diperbarui!');
    }

    public function destroy(Golongan $golongan)
    {
        $golongan->delete();
        return redirect()->back()->with('success', 'Data Golongan berhasil dihapus!');
    }
}