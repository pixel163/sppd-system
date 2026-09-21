<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // public function index()
    // {
    //     $items = Role::latest()->paginate(10);
    //     $title = 'Role';
    //     $routeName = 'role'; // Sesuaikan dengan nama route resource di web.php

    //     return view('master.index', compact('items', 'title', 'routeName'));
    // }
    public function index()
    {
        // $roles = Role::with('creator')->latest()->paginate(10);
        $roles = Role::latest()->paginate(10);
        // return view('master.index', compact('roles'));
        return view('master.role.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:role,name',
        ]);

        Role::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Role berhasil ditambahkan!');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:role,name,' . $role->id,
        ]);

        $role->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Role berhasil diperbarui!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Data Role berhasil dihapus!');
    }
}