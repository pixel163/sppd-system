<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // public function index()
    // {
    //     $items = Department::latest()->paginate(10);
    //     $title = 'Department';
    //     $routeName = 'department'; // Sesuaikan dengan nama route resource di web.php

    //     return view('master.index', compact('items', 'title', 'routeName'));
    // }
    public function index()
    {
        $departments = Department::with('created_by')->latest()->paginate(10);
        // $departments = Department::latest()->paginate(10);
        // return view('master.index', compact('departments'));
        return view('master.department.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:department,name',
            'is_active' => 'nullable|boolean',
        ]);

        Department::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Department berhasil ditambahkan!');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:department,name,' . $department->id,
            'is_active' => 'nullable|boolean',
        ]);

        $department->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Department berhasil diperbarui!');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->back()->with('success', 'Data Department berhasil dihapus!');
    }
}