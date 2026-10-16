<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Golongan;
use App\Models\Department;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::with(['golongan', 'department'])->latest()->paginate(10);
        
        return view('master.jabatan.index', compact('jabatans'));
    }
    // public function index()
    // {
        
    //     $jabatans = Jabatan::latest()->paginate(10);

    //     $golongan = Golongan::all();
    //     $department = Department::all();
        
    //     return view('master.jabatan.index', compact('jabatans','golongan','department'));
    // }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:jabatan,name',
        ]);

        Jabatan::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Jabatan berhasil ditambahkan!');
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:jabatan,name,' . $jabatan->id,
        ]);

        $jabatan->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Jabatan berhasil diperbarui!');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->back()->with('success', 'Data Jabatan berhasil dihapus!');
    }
}