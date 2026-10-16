<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            [
                'golongan_id'  => '1',
                'department_id'  => '1',
                'name'        => 'HRGA',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'golongan_id'  => '2',
                'name'        => 'Manager',
                'department_id'  => '1',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'golongan_id'  => '1',
                'department_id'  => '1',
                'name'        => 'Staff',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($jabatans as $jabatan) {
            DB::table('jabatan')->updateOrInsert(
                ['name' => $jabatan['name']],
                $jabatan                      
            );
        }
    }
}

// let documentButtons = `
//         <button type="button" 
//                 data-item="${safeItemJson}"
//                 onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
//                 class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
//             <span>👁</span> Lihat
//         </button>
//     `;

//     if (isHRGA && !isMyDocument) {

//         if (statusNum === 1) {
//             if (['form_sppd', 'sppd'].includes(type) && isSubmitterManager) {
//                 documentButtons += `
//                     <a href="/sppd/${itemId}/approves" class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-medium text-white hover:bg-blue-700">
//                         Proses
//                     </a>
//                 `;
//             }
//         }

//         else if (statusNum === 2) {
//             if (['form_sppd', 'sppd'].includes(type)) {
//                 documentButtons += `
//                     <button type="button" 
//                             data-item="${safeItemJson}"
//                             onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
//                             class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
//                         <span>🖨</span> Cetak
//                     </button>
//                 `;
//             } else if (['form_ilpd', 'ilpd'].includes(type)) {
//                 documentButtons += `
//                     <a href="/ilpd/${itemId}" class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-medium text-white hover:bg-blue-700">
//                         Proses
//                     </a>
//                 `;
//             }
//         }

//         else if ([3, 5, 6].includes(statusNum)) {

//             if (statusNum === 3) {
//                 const today = new Date().toISOString().split('T')[0];
//                 const tglBerangkat = item.ilpd?.tanggal_awal || item.tanggal_mulai || null;

//                 if (['form_ilpd', 'ilpd'].includes(type) && tglBerangkat && tglBerangkat > today) {
//                     documentButtons += `
//                         <a href="/ilpd/${itemId}/edit" class="flex items-center gap-1.5 rounded-lg bg-amber-500 px-4 py-2 text-[13px] font-medium text-white hover:bg-amber-600">
//                             <span>✏️</span> Edit
//                         </a>
//                     `;
//                 }
//             }

//             documentButtons += `
//                 <button type="button" 
//                         data-item="${safeItemJson}"
//                         onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
//                         class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
//                     <span>🖨</span> Cetak
//                 </button>
//             `;
//         }
//     }

//     else {

//         if (statusNum === 2) {
//             if (['form_sppd', 'sppd'].includes(type)) {
//                 documentButtons += `
//                     <button type="button" 
//                             data-item="${safeItemJson}"
//                             onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
//                             class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
//                         <span>🖨</span> Cetak
//                     </button>
//                 `;
//             }
//         }

//         else if ([3, 5, 6].includes(statusNum)) {
//             documentButtons += `
//                 <button type="button" 
//                         data-item="${safeItemJson}"
//                         onclick="printItem('${type}', JSON.parse(decodeURIComponent(this.dataset.item)))"
//                         class="flex items-center gap-1.5 rounded-lg border ${doc.border || 'border-slate-300'} px-4 py-2 text-[13px] font-medium ${doc.iconColor || 'text-slate-700'} hover:bg-slate-50">
//                     <span>🖨</span> Cetak
//                 </button>
//             `;
//         }
//     }