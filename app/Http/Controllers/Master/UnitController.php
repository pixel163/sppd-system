<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        
        $units = Unit::latest()->paginate(10);
        
        return view('master.unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:unit,name',
        ]);

        Unit::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Unit berhasil ditambahkan!');
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:unit,name,' . $unit->id,
        ]);

        $unit->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Unit berhasil diperbarui!');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->back()->with('success', 'Data Unit berhasil dihapus!');
    }
}