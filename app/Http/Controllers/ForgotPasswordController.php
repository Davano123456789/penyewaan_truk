<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form lupa password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgotPassword');
    }

    /**
     * Kirim link reset password ke email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar dalam sistem',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Kirim link reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset kata sandi telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset. Silakan coba lagi.']);
    }
}