<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Keperluan;
use Illuminate\Http\Request;

class KeperluanController extends Controller
{
    // public function index()
    // {
    //     $items = Keperluan::latest()->paginate(10);
    //     $title = 'Keperluan';
    //     $routeName = 'keperluan'; // Sesuaikan dengan nama route resource di web.php

    //     return view('master.index', compact('items', 'title', 'routeName'));
    // }
    public function index()
    {
        $keperluans = Keperluan::with('creator')->latest()->paginate(10);
        // $keperluans = Keperluan::latest()->paginate(10);
        // return view('master.index', compact('keperluans'));
        return view('master.keperluan.index', compact('keperluans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:keperluan,name',
        ]);

        Keperluan::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Keperluan berhasil ditambahkan!');
    }

    public function update(Request $request, Keperluan $keperluan)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:keperluan,name,' . $keperluan->id,
        ]);

        $keperluan->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Keperluan berhasil diperbarui!');
    }

    public function destroy(Keperluan $keperluan)
    {
        $keperluan->delete();
        return redirect()->back()->with('success', 'Data Keperluan berhasil dihapus!');
    }
}