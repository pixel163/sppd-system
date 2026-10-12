<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MasterImport;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    public function importMaster(Request $request)
    {
        $request->validate([
            'Master' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Excel::import(new MasterImport, $request->file('Master'));
            });

            return back()->with('success', 'Master Data HR berhasil di-import seluruhnya!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\KaryawanImport;
// use App\Imports\AreaImport;

// class MasterDataController extends Controller
// {
//     // Halaman Form Upload
//     public function index()
//     {
//         return view('master-data.index');
//     }

//     // Process Import Karyawan
//     public function importKaryawan(Request $request)
//     {
//         $request->validate([
//             'file_karyawan' => 'required|mimes:xlsx,xls,csv|max:5120',
//         ]);

//         try {
//             Excel::import(new KaryawanImport, $request->file('file_karyawan'));
//             return back()->with('success', 'Master Data Karyawan berhasil di-import!');
//         } catch (\Exception $e) {
//             return back()->with('error', 'Gagal import Karyawan: ' . $e->getMessage());
//         }
//     }

//     // Process Import Lokasi
//     public function importArea(Request $request)
//     {
//         $request->validate([
//             'file_area' => 'required|mimes:xlsx,xls,csv|max:5120',
//         ]);

//         try {
//             Excel::import(new AreaImport, $request->file('file_area'));
//             return back()->with('success', 'Master Data Lokasi berhasil di-import!');
//         } catch (\Exception $e) {
//             return back()->with('error', 'Gagal import Area: ' . $e->getMessage());
//         }
//     }
// }

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class MasterDataController extends Controller
// {
//     //
// }
