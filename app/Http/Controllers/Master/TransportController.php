<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    // public function index()
    // {
    //     $items = Transport::latest()->paginate(10);
    //     $title = 'Transport';
    //     $routeName = 'transport'; // Sesuaikan dengan nama route resource di web.php

    //     return view('master.index', compact('items', 'title', 'routeName'));
    // }
    public function index()
    {
        // $transports = Transport::with('creator')->latest()->paginate(10);
        $transports = Transport::latest()->paginate(10);
        // return view('master.index', compact('transports'));
        return view('master.transport.index', compact('transports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:transport,name',
        ]);

        Transport::create([
            'name'       => $request->name,
            'is_active'  => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Data Transport berhasil ditambahkan!');
    }

    public function update(Request $request, Transport $transport)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:transport,name,' . $transport->id,
        ]);

        $transport->update([
            'name'      => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Data Transport berhasil diperbarui!');
    }

    public function destroy(Transport $transport)
    {
        $transport->delete();
        return redirect()->back()->with('success', 'Data Transport berhasil dihapus!');
    }
}