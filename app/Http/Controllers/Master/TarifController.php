<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    // public function index()
    // {
    //     $items = Tarif::latest()->paginate(10);
    //     $title = 'Tarif';
    //     $routeName = 'tarif'; // Sesuaikan dengan nama route resource di web.php

    //     return view('master.index', compact('items', 'title', 'routeName'));
    // }
    public function index()
    {
        // $tarifs = Tarif::with('creator')->latest()->paginate(10);
        $tarifs = Tarif::with('kotaKategori','golongan')->latest()->paginate(10);
        // return view('master.index', compact('tarifs'));
        return view('master.tarif.index', compact('tarifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tarif,name',
        ]);

        Tarif::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Tarif berhasil ditambahkan!');
    }

    public function update(Request $request, Tarif $tarif)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tarif,name,' . $tarif->id,
        ]);

        $tarif->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Tarif berhasil diperbarui!');
    }

    public function destroy(Tarif $tarif)
    {
        $tarif->delete();
        return redirect()->back()->with('success', 'Data Tarif berhasil dihapus!');
    }
}