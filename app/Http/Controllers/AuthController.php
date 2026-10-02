<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->jabatan->name) {
                'staff'   => redirect()->route('dashboard'),
                'manager' => redirect()->route('dashboard'),
                'hrga'    => redirect()->route('dashboard'),
                default   => redirect('/dashboard'),
            };
        }

        return back()->with('error', 'Email atau password salah.')->withInput($request->only('email'));
        // return back()->with('warning', 'Email atau password salah.')->withInput($request->only('email'));
        // return back()->withErrors(['email' => 'Email atau password salah.',])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. Opsi A: Jika User Mengunggah File Gambar (PNG/JPG)
        if ($request->hasFile('signature_file')) {
            $request->validate([
                'signature_file' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            ]);

            // Hapus TTD lama jika ada
            if ($user->signature) {
                Storage::disk('public')->delete($user->signature);
            }

            $path = $request->file('signature_file')->store('signatures', 'public');
            $user->update(['signature' => $path]);

            return back()->with('success', 'Master TTD berhasil diperbarui via Upload File!');
        }

        // 2. Opsi B: Jika User Menggambar via Canvas (Base64 String)
        if ($request->input('signature_canvas')) {
            $dataUrl = $request->input('signature_canvas');

            // Format Base64: data:image/png;base64,iVBORw0KGgo...
            list($type, $data) = explode(';', $dataUrl);
            list(, $data)      = explode(',', $data);
            $decodedData = base64_decode($data);

            // Hapus TTD lama jika ada
            if ($user->signature) {
                Storage::disk('public')->delete($user->signature);
            }

            $filename = 'signatures/sig_' . $user->id . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $decodedData);

            $user->update(['signature' => $filename]);

            return back()->with('success', 'Master TTD berhasil diperbarui via Coretan Canvas!');
        }

        return back()->with('error', 'Silakan unggah file TTD atau buat tanda tangan di area canvas.');
    }

    public function registerForm()
    {
        // Mengambil data master departemen dan jabatan untuk dropdown
        $departments = Department::all();
        $jabatans = Jabatan::all();

        // dd($departments, $jabatans);

        return view('register', compact('departments', 'jabatans'));
    }

    // Memproses data pendaftaran akun baru
    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'nik'           => 'required|numeric|digits_between:5,16|unique:users,nik',
            'department_id' => 'required|exists:department,id',
            'jabatan_id'    => 'required|exists:jabatan,id',
            'password'      => 'required|string|min:8|confirmed',
        ], [
            'nik.unique'        => 'NIK sudah terdaftar di sistem.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Cek data Jabatan & Departemen dari database
        $jabatan = \App\Models\Jabatan::find($request->jabatan_id);

        // Default role_id untuk Staff = 1
        $roleId = 1;
        $golonganId = 1;

        // Bersihkan teks nama jabatan & dept untuk pengecekan (case-insensitive)
        $namaJabatan = strtolower($jabatan->name ?? $jabatan->nama ?? '');

        // Cek jika Jabatan mengandung kata 'manager' ATAU Departemen mengandung 'hr' / 'ga'
        if (str_contains($namaJabatan, 'Manager') || str_contains($namaJabatan, 'HRGA')) {$roleId = 2;} // Set ke Manager & HRGA}
        if (str_contains($namaJabatan, 'Manager') || str_contains($namaJabatan, 'HRGA')) {$golonganId = 2;} // Set ke Manager & HRGA}

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'nik'           => $request->nik,
            'role_id'       => $roleId,
            'golongan_id'   => $golonganId,
            'department_id' => $request->department_id,
            'jabatan_id'    => $request->jabatan_id,
            'password'      => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran akun berhasil! Silakan masuk.');
    }
}