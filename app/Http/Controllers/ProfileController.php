<?php

// Menentukan namespace lokasi controller ini berada
namespace App\Http\Controllers;

// Mengimpor class yang dibutuhkan
use App\Http\Requests\ProfileUpdateRequest; // Request khusus untuk validasi update profil
use Illuminate\Http\RedirectResponse;        // Untuk tipe response redirect
use Illuminate\Http\Request;                 // Untuk menangani HTTP request
use Illuminate\Support\Facades\Auth;         // Untuk autentikasi (login/logout)
use Illuminate\Support\Facades\Redirect;     // Untuk redirect halaman
use Illuminate\View\View;                    // Untuk return view

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman form edit profil pengguna
     */
    public function edit(Request $request): View
    {
        // Mengembalikan view 'profile.edit' dan mengirim data user yang sedang login
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Mengupdate informasi profil pengguna
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Mengisi data user dengan data yang sudah tervalidasi
        $request->user()->fill($request->validated());

        // Jika email berubah, maka status verifikasi email di-reset
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Menyimpan perubahan ke database
        $request->user()->save();

        // Redirect kembali ke halaman edit profile dengan status sukses
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi password sebelum menghapus akun
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Ambil data user yang sedang login
        $user = $request->user();

        // Logout user
        Auth::logout();

        // Hapus akun user dari database
        $user->delete();

        // Hapus session lama dan buat token baru
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return Redirect::to('/');
    }
}
