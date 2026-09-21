<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\KotaKategori;
use Illuminate\Http\Request;

class KotaKategoriController extends Controller
{
    // public function index()
    // {
    //     $items = KotaKategori::latest()->paginate(10);
    //     $title = 'KotaKategori';
    //     $routeName = 'kotakategori'; // Sesuaikan dengan nama route resource di web.php

    //     return view('master.index', compact('items', 'title', 'routeName'));
    // }
    public function index()
    {
        // $kotakategoris = KotaKategori::with('creator')->latest()->paginate(10);
        $kotakategoris = KotaKategori::latest()->paginate(10);
        // return view('master.index', compact('kotakategori'));
        return view('master.kotakategori.index', compact('kotakategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kotakategori,name',
        ]);

        KotaKategori::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data KotaKategori berhasil ditambahkan!');
    }

    public function update(Request $request, KotaKategori $kotakategoris)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kotakategori,name,' . $kotakategoris->id,
        ]);

        $kotakategoris->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data KotaKategori berhasil diperbarui!');
    }

    public function destroy(KotaKategori $kotakategoris)
    {
        $kotakategoris->delete();
        return redirect()->back()->with('success', 'Data KotaKategori berhasil dihapus!');
    }
}