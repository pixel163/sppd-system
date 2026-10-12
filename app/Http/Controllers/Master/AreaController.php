<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::latest()->paginate(10);
        
        return view('master.area.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:area,name',
        ]);

        Area::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Area berhasil ditambahkan!');
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:area,name,' . $area->id,
        ]);

        $area->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Area berhasil diperbarui!');
    }

    public function destroy(Area $areas)
    {
        $areas->delete();
        return redirect()->back()->with('success', 'Data Area berhasil dihapus!');
    }
}