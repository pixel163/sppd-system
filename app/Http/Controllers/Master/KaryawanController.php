<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = User::latest()->paginate(10);
        
        return view('master.karyawan.index', compact('karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:karyawan,name',
        ]);

        User::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data User berhasil ditambahkan!');
    }

    public function update(Request $request, User $karyawan)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:karyawan,name,' . $karyawan->id,
        ]);

        $karyawan->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data User berhasil diperbarui!');
    }

    public function destroy(User $karyawans)
    {
        $karyawans->delete();
        return redirect()->back()->with('success', 'Data User berhasil dihapus!');
    }
}