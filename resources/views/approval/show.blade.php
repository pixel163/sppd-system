<div class="container mx-auto p-6">
    
    <!-- 1. HEADER TAB NAVIGATION -->
    <div class="flex border-b border-gray-200 mb-6">
        <button id="tab-sppd-btn" onclick="switchTab('sppd')" 
                class="py-3 px-6 font-bold text-blue-600 border-b-2 border-blue-600">
            📄 Form 1: SPPD (Pengajuan Dinas)
        </button>
        <button id="tab-ilpd-btn" onclick="switchTab('ilpd')" 
                class="py-3 px-6 font-medium text-gray-500 hover:text-gray-700">
            💰 Form 2: ILPD (Rincian Biaya)
        </button>
    </div>

    <!-- 2. KONTEN TAB 1 (Panggil Blade SPPD) -->
    <div id="tab-sppd-content" class="tab-content">
        @include('sppd.approve')
    </div>

    <!-- 3. KONTEN TAB 2 (Panggil Blade ILPD - Hidden awal) -->
    <div id="tab-ilpd-content" class="tab-content hidden">
        @include('ilpd.approve')
    </div>

    <!-- 4. FOOTER FIXED (Tombol Action Manager) -->
    <form action="{{ route('sppd.approve', $sppd->id) }}" method="POST" class="mt-8 pt-4 border-t">
        @csrf
        <div class="flex justify-end gap-4">
            <button type="submit" name="action" value="reject" class="btn-danger">
                ✗ Tolak Pengajuan
            </button>
            <button type="submit" name="action" value="approve" class="btn-success">
                ✓ Setujui SPPD & ILPD
            </button>
        </div>
    </form>
</div>

<!-- JavaScript Sederhana Pindah Tab -->
<script>
    function switchTab(tabName) {
        // Sembunyikan semua konten
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Tampilkan konten yang dipilih
        document.getElementById('tab-' + tabName + '-content').classList.remove('hidden');
        
        // Update style tombol tab (Aktif / Non-aktif)
        // ... (bisa diatur sesuai class Tailwind/CSS Anda)
    }
</script>