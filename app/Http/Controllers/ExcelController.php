<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends Controller
{
    public function test()
    {
        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Ambil sheet pertama
        $sheet = $spreadsheet->getActiveSheet();

        // Isi data
        $sheet->setCellValue('A1', 'Laporan SPPD');
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Nama');
        $sheet->setCellValue('C2', 'Departemen');
        $sheet->setCellValue('D2', 'Kota');

        $sheet->setCellValue('A3', '1');
        $sheet->setCellValue('B3', 'Ahmad Ramzi');
        $sheet->setCellValue('C3', 'IT');
        $sheet->setCellValue('D3', 'Jakarta');

        // Buat file Excel
        $writer = new Xlsx($spreadsheet);

        // Download
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'tes-sppd.xlsx');
    }
}