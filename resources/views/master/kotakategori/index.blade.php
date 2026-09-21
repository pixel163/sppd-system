@extends('layouts.app')

@section('content')
<div class="p-6" x-data="{ openCreate: false, openEdit: false, editId: null, editName: '', editActive: true }">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Master Data Kota Kategori</h1>
            <p class="text-sm text-slate-500">Kelola daftar kota kategori perjalanan dinas</p>
        </div>
        <button @click="openCreate = true" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Kota Kategori
        </button>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Data -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama Kota Kategori</th>
                    <th class="px-6 py-4">Dibuat Oleh</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                @forelse($kotakategoris as $index => $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-slate-500">{{ $kotakategoris->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $item->name }}</td>
                        {{-- <td class="px-6 py-4 text-slate-500">
                            {{ $item->creator ? $item->creator->name : 'Sistem/HRGA' }}
                        </td> --}}
                        <td class="px-6 py-4 text-slate-500">
                            HRGA / Admin
                        </td>
                        <td class="px-6 py-4">
                            @if($item->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Tombol Edit -->
                                <button @click="openEdit = true; editId = {{ $item->id }}; editName = '{{ $item->name }}'; editActive = {{ $item->is_active ? 'true' : 'false' }}" 
                                        class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-colors">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                
                                <!-- Tombol Hapus -->
                                <form action="{{ route('master.kotakategori.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada data kota kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $kotakategoris->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH DATA -->
    <div x-show="openCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl" @click.away="openCreate = false">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Tambah Kota Kategori Baru</h2>
            <form action="{{ route('master.kotakategori.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kota Kategori</label>
                        <input type="text" name="name" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Misal: Audit Lapangan">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active_add" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_active_add" class="text-sm text-slate-600">Status Aktif</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="openCreate = false" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT DATA -->
    <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl" @click.away="openEdit = false">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Edit Kota Kategori</h2>
            <form :action="'{{ url('master/kotakategori') }}/' + editId" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kota Kategori</label>
                        <input type="text" name="name" x-model="editName" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active_edit" value="1" :checked="editActive" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_active_edit" class="text-sm text-slate-600">Status Aktif</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="openEdit = false" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection