@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-sm border border-slate-200">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Pengaturan Profil & Master TTD</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-100 text-emerald-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Preview TTD Aktif saat ini -->
    <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Master TTD Digital Saat Ini</label>
        @if($user->signature)
            <div class="p-2 bg-white inline-block border rounded-md">
                <img src="{{ asset('storage/' . $user->signature) }}" alt="Master TTD" class="h-24 object-contain">
            </div>
        @else
            <p class="text-sm text-slate-400 italic">Belum ada Master TTD yang tersimpan.</p>
        @endif
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="formSignature">
        @csrf
        <input type="hidden" name="signature_canvas" id="signatureCanvasInput">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Opsi 1: Coretan TTD Canvas -->
            <div class="border rounded-lg p-4 bg-slate-50">
                <h3 class="font-semibold text-slate-700 text-sm mb-2">Cara 1: Tanda Tangan Langsung</h3>
                <div class="border border-slate-300 rounded bg-white relative">
                    <canvas id="signature-pad" width="350" height="180" class="w-full h-44 touch-none cursor-crosshair"></canvas>
                </div>
                <div class="mt-2 flex justify-between items-center">
                    <button type="button" id="clearCanvas" class="text-xs text-rose-600 hover:underline">Bersihkan Canvas</button>
                    <span class="text-[11px] text-slate-400">Gunakan mouse / layar sentuh</span>
                </div>
            </div>

            <!-- Opsi 2: Upload File PNG -->
            <div class="border rounded-lg p-4 bg-slate-50 flex flex-col justify-between">
                <div>
                    <h3 class="font-semibold text-slate-700 text-sm mb-2">Cara 2: Upload File TTD</h3>
                    <p class="text-xs text-slate-500 mb-3">Unggah scan TTD fisik. Disarankan format PNG dengan latar transparan.</p>
                    <input type="file" name="signature_file" accept="image/png,image/jpeg" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <p class="text-[11px] text-slate-400">*Ukuran maksimal file: 2MB</p>
                </div>
            </div>

        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" id="btnSave" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg text-sm hover:bg-indigo-700 transition">
                Simpan Master TTD
            </button>
        </div>
    </form>
</div>

<!-- Script Signature Pad (Canvas TTD) -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(15, 23, 42)'
        });

        document.getElementById('clearCanvas').addEventListener('click', function () {
            signaturePad.clear();
        });

        document.getElementById('formSignature').addEventListener('submit', function (e) {
            // Jika canvas diisi, konversi ke Base64 string
            if (!signaturePad.isEmpty()) {
                document.getElementById('signatureCanvasInput').value = signaturePad.toDataURL('image/png');
            }
        });
    });
</script>
@endsection