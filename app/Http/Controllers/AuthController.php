<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->jabatan->name) {
                'staff' => redirect()->route('dashboard'),
                'manager' => redirect()->route('dashboard'),
                'hrga' => redirect()->route('dashboard'),
                default => redirect('/dashboard'),
            };
        }

        // return back()->with('warning', 'Email atau password salah.')->withInput($request->only('email'));
        return back()->withErrors(['email' => 'Email atau password salah.',])->onlyInput('email');
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
}