<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - SPPD System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-xl w-full space-y-6 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-slate-800">Pendaftaran Akun SPPD</h2>
            <p class="text-sm text-slate-500 mt-1">Isi data diri Anda sesuai dengan data resmi perusahaan</p>
        </div>

        <!-- Alert Error Laravel -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm space-y-1">
                <p class="font-semibold">Mohon perbaiki kesalahan berikut:</p>
                <ul class="list-disc list-inside text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Budi Santoso"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1c61e7] focus:border-transparent">
            </div>

            <!-- Grid NIK & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- NIK Karyawan (Hanya Angka, Maxlength 9) -->
                <div>
                    <label for="nik" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        NIK Karyawan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required maxlength="9"
                        placeholder="Contoh: 317101230"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1c61e7] focus:border-transparent">
                    <span class="text-[11px] text-slate-400 mt-0.5 block">Hanya angka (Maks 9 digit)</span>
                </div>

                <!-- Email Perusahaan -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Email Kantor <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        placeholder="nama@perusahaan.com"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1c61e7] focus:border-transparent">
                </div>
            </div>

            <!-- Grid Departemen & Jabatan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Departemen -->
                <div>
                    <label for="department_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Departemen <span class="text-red-500">*</span>
                    </label>
                    <select id="department_id" name="department_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1c61e7] focus:border-transparent">
                        <option value="" disabled selected>-- Pilih Departemen --</option>
                        @if(isset($departments) && $departments->count() > 0)
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        @else
                            {{-- <option value="1">Human Capital / HR</option>
                            <option value="2">Finance & Accounting</option>
                            <option value="3">Information Technology</option>
                            <option value="4">General Affair</option> --}}
                        @endif
                    </select>
                </div>

                <!-- Jabatan -->
                <div>
                    <label for="jabatan_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Jabatan <span class="text-red-500">*</span>
                    </label>
                    <select id="jabatan_id" name="jabatan_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1c61e7] focus:border-transparent">
                        <option value="" disabled selected>-- Pilih Jabatan --</option>
                        @if(isset($jabatans) && $jabatans->count() > 0)
                            @foreach ($jabatans as $jab)
                                <option value="{{ $jab->id }}" {{ old('jabatan_id') == $jab->id ? 'selected' : '' }}>
                                    {{ $jab->name }}
                                </option>
                            @endforeach
                        @else
                            {{-- <option value="1">Staff / Officer</option>
                            <option value="2">Senior Staff</option>
                            <option value="3">Supervisor</option>
                            <option value="4">Manager</option> --}}
                        @endif
                    </select>
                </div>
            </div>

            <!-- Grid Password & Konfirmasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1c61e7] focus:border-transparent">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1c61e7] focus:border-transparent">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full h-12 flex items-center justify-center gap-2 bg-[#1c61e7] hover:bg-[#1857d1] text-white font-semibold rounded-xl text-[15px] transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1c61e7] focus:ring-offset-2">
                    <span>Daftar Akun Baru</span>
                </button>
            </div>

            <!-- Footer Link -->
            <p class="text-center text-xs text-slate-500 mt-4">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-semibold text-[#1c61e7] hover:underline">Masuk di sini</a>
            </p>
        </form>
    </div>

</body>
</html>