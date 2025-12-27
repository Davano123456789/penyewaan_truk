<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class ResetPasswordController extends Controller
{
    /**
     * Tampilkan form reset password
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.resetPassword', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'token.required' => 'Token reset tidak valid',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Kata sandi baru harus diisi',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok',
            'password.min' => 'Kata sandi minimal 8 karakter',
        ]);

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'kata_sandi' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Kata sandi berhasil direset! Silakan login dengan kata sandi baru Anda.')
            : back()->withErrors(['email' => 'Link reset tidak valid atau sudah kadaluarsa. Silakan kirim ulang link reset.']);
    }
}