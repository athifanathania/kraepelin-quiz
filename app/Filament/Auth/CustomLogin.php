<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomLogin extends Login
{
    // Kita "bajak" proses pengambilan data login
    protected function getCredentialsFromFormData(array $data): array
    {
        $email = $data['email'];
        $password = $data['password'];

        // 1. Cari User di Database
        $user = User::where('email', $email)->first();

        // 2. Cek apakah User Ada & Password Benar
        if ($user && Hash::check($password, $user->password)) {
            
            // 3. Pengecekan KUNCI: Apakah akun aktif?
            if (! $user->is_active) {
                // Jika tidak aktif, lempar error (muncul notifikasi merah di form)
                throw ValidationException::withMessages([
                    'data.email' => 'Akun Anda dinonaktifkan. Silakan hubungi Admin.',
                ]);
            }
        }

        // Jika lolos (atau jika password salah), kembalikan data biar diproses Filament seperti biasa
        return [
            'email' => $email,
            'password' => $password,
        ];
    }
}