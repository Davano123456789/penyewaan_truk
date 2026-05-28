<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\SopirController;
use App\Http\Controllers\ArmadaController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ParkirController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\MasukanController;
use App\Http\Controllers\TagihanController;
use Illuminate\Http\Request; // ✅ ini WAJIB
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PenyewaanAdminController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PembayaranAdminController;
use App\Http\Controllers\PenugasanAdminController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "web" middleware group. Make something great! | */

Route::get('/', [FrontController::class , 'index'])->name('home');
// Halaman form pemesanan
Route::get('/pemesanan', [FrontController::class , 'pemesanan'])->name('pemesanan')->middleware('auth');

// Submit form ke keranjang
Route::post('/pemesanan', [FrontController::class , 'storePemesanan'])->name('pemesanan.store')->middleware('auth');
// Halaman keranjang
Route::get('/api/armada-tersedia', [FrontController::class , 'getArmadaTersedia']);
Route::get('/daftar-armada', [FrontController::class , 'daftarArmada'])->name('daftarArmada');
Route::get('/armada/{id}', [FrontController::class , 'detailArmada'])->name('armada.detail');

// auth
// Route register (protect with guest middleware so authenticated users cannot access)
Route::get('/login', [FrontController::class , 'login'])->name('login')->middleware('guest');
Route::post('/login', [FrontController::class , 'loginStore'])->name('login.store')->middleware('guest');
Route::get('/register', [FrontController::class , 'register'])->name('register')->middleware('guest');
Route::post('/register', [FrontController::class , 'registerStore'])->name('register.store')->middleware('guest');

// Route email verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'Email berhasil diverifikasi! Selamat datang!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi berhasil dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/forgot-password', [ForgotPasswordController::class , 'showLinkRequestForm'])
    ->name('password.request')->middleware('guest');

Route::post('/forgot-password', [ForgotPasswordController::class , 'sendResetLinkEmail'])
    ->name('password.email')->middleware('guest');

// Route reset password
Route::get('/reset-password/{token}', [ResetPasswordController::class , 'showResetForm'])
    ->name('password.reset')->middleware('guest');

Route::post('/reset-password', [ResetPasswordController::class , 'reset'])
    ->name('password.update')->middleware('guest');
// Route logout
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');



// dashboard
Route::get('/dashboard', [DashboardController::class , 'dashboard'])->name('dashboard');
// mitra kerja
// Mitra
Route::get('/dashboard/mitra', [MitraController::class , 'index'])->name('mitra.index');
Route::get('/dashboard/mitra/tambah', [MitraController::class , 'tambah'])->name('mitra.tambah');
Route::post('/dashboard/mitra', [MitraController::class , 'store'])->name('mitra.store');
Route::get('/dashboard/mitra/{id}', [MitraController::class , 'show'])->name('mitra.show');
Route::get('/dashboard/mitra/{id}/edit', [MitraController::class , 'edit'])->name('mitra.edit');
Route::put('/dashboard/mitra/{id}', [MitraController::class , 'update'])->name('mitra.update');
Route::delete('/dashboard/mitra/{id}', [MitraController::class , 'destroy'])->name('mitra.destroy');
// keunggulan
use App\Http\Controllers\KeunggulanController;
Route::get('/dashboard/keunggulan', [KeunggulanController::class , 'index'])->name('keunggulan.index');
Route::get('/dashboard/keunggulan/tambah', [KeunggulanController::class , 'tambah'])->name('keunggulan.tambah');
Route::post('/dashboard/keunggulan', [KeunggulanController::class , 'store'])->name('keunggulan.store');
Route::get('/dashboard/keunggulan/{id}', [KeunggulanController::class , 'show'])->name('keunggulan.show');
Route::get('/dashboard/keunggulan/{id}/edit', [KeunggulanController::class , 'edit'])->name('keunggulan.edit');
Route::put('/dashboard/keunggulan/{id}', [KeunggulanController::class , 'update'])->name('keunggulan.update');
Route::delete('/dashboard/keunggulan/{id}', [KeunggulanController::class , 'destroy'])->name('keunggulan.destroy');
// armada
Route::get('/dashboard/armada', [ArmadaController::class , 'index'])->name('armada.index');
Route::get('/dashboard/armada/tambah', [ArmadaController::class , 'tambah'])->name('armada.tambah');
Route::delete('/dashboard/armada/{id}', [ArmadaController::class , 'destroy'])->name('armada.destroy');
Route::get('/dashboard/armada/{id}', [ArmadaController::class , 'show'])->name('armada.show');
Route::post('/dashboard/armada/store', [ArmadaController::class , 'store'])->name('armada.store');
Route::get('/dashboard/armada/edit/{id}', [ArmadaController::class , 'edit'])->name('armada.edit');
Route::put('/dashboard/armada/update/{id}', [ArmadaController::class , 'update'])->name('armada.update');

Route::get('/dashboard/parkir', [ParkirController::class , 'index'])->name('parkir.index');
Route::get('/dashboard/parkir/tambah', [ParkirController::class , 'tambah'])->name('parkir.tambah');
Route::get('/dashboard/parkir/{id}', [ParkirController::class , 'show'])->name('parkir.show');
Route::get('/dashboard/parkir/{id}/armada', [ParkirController::class , 'armada'])->name('parkir.armada');
Route::delete('/dashboard/parkir/{id}', [ParkirController::class , 'destroy'])->name('parkir.destroy');
Route::post('/dashboard/parkir/tambah', [ParkirController::class , 'store'])->name('parkir.store');
Route::get('/dashboard/parkir/edit/{id}', [ParkirController::class , 'edit'])->name('parkir.edit');
Route::put('/dashboard/parkir/update/{id}', [ParkirController::class , 'update'])->name('parkir.update');
// sopir
Route::get('/dashboard/sopir', [SopirController::class , 'index'])->name('sopir.index');
Route::get('/dashboard/sopir/tambah', [SopirController::class , 'create'])->name('sopir.create');
Route::post('/dashboard/sopir', [SopirController::class , 'store'])->name('sopir.store');
Route::get('/dashboard/sopir/{id}', [SopirController::class , 'show'])->name('sopir.show');
Route::get('/dashboard/sopir/{id}/edit', [SopirController::class , 'edit'])->name('sopir.edit');
Route::put('/dashboard/sopir/{id}', [SopirController::class , 'update'])->name('sopir.update');
Route::delete('/dashboard/sopir/{id}', [SopirController::class , 'destroy'])->name('sopir.destroy');

// client
Route::get('/dashboard/client', [ClientController::class , 'index'])->name('client.index');
Route::get('/dashboard/client/{id}', [ClientController::class , 'show'])->name('client.show');
Route::delete('/dashboard/client/{id}', [ClientController::class , 'destroy'])->name('client.destroy');
// penyewaan
Route::get('/dashboard/penyewaan-admin', [PenyewaanAdminController::class , 'index'])->name('penyewaanAdmin.index');
Route::get('/dashboard/penyewaan-admin/{id}', [PenyewaanAdminController::class , 'show'])->name('penyewaanAdmin.show');
Route::get('/dashboard/penyewaan-admin/{id}/invoice', [PenyewaanAdminController::class , 'cetakInvoice'])->name('penyewaanAdmin.invoice');
Route::post('/dashboard/penyewaan-admin/{id}/konfirmasi', [PenyewaanAdminController::class , 'konfirmasiPembayaran'])->name('penyewaanAdmin.konfirmasi');
Route::post('/dashboard/penyewaan-admin/{id}/tolak', [PenyewaanAdminController::class , 'tolakPembayaran'])->name('penyewaanAdmin.tolak');
Route::delete('/dashboard/penyewaan-admin/{id}', [PenyewaanAdminController::class , 'destroy'])->name('penyewaanAdmin.destroy');

// Penugasan Admin (Validasi Bukti Selesai Sopir)
Route::get('/dashboard/penugasan-admin', [PenugasanAdminController::class , 'index'])->name('penugasanAdmin.index');
Route::post('/dashboard/penugasan-admin/{id}/validasi', [PenugasanAdminController::class , 'validasi'])->name('penugasanAdmin.validasi');
Route::post('/dashboard/penugasan-admin/{id}/tolak', [PenugasanAdminController::class , 'tolak'])->name('penugasanAdmin.tolak');
Route::get('/dashboard/penyewaan', [PenyewaanController::class , 'index'])->name('penyewaan.index');
// Route Pembayaran
Route::get('/dashboard/pembayaran/riwayat', [PenyewaanController::class , 'riwayatPembayaran'])->name('pembayaran.riwayat');
Route::get('/dashboard/pembayaran/detail/{id}', [PenyewaanController::class , 'detailPembayaran'])->name('pembayaran.detail');
Route::get('/dashboard/pembayaran/{penyewaan}', [PenyewaanController::class , 'showPembayaran'])->name('pembayaran.show');
Route::post('/dashboard/pembayaran/{penyewaan}', [PenyewaanController::class , 'storePembayaran'])->name('pembayaran.store');
Route::delete('/dashboard/penyewaan/{penyewaan}', [PenyewaanController::class , 'destroy'])->name('penyewaan.destroy');
// Pembayaran Admin (daftar pembayaran)
Route::get('/dashboard/pembayaran-admin', [PembayaranAdminController::class , 'index'])->name('pembayaranAdmin.index');
Route::get('/dashboard/pembayaran-admin/{id}', [PembayaranAdminController::class , 'show'])->name('pembayaranAdmin.show');
// tagihan
Route::get('/dashboard/tagihan', [TagihanController::class , 'index'])->name('tagihan.index');
Route::get('/dashboard/tagihan/tambah', [TagihanController::class , 'tambah'])->name('tagihan.tambah');
// masukan
Route::get('/dashboard/masukan', [MasukanController::class , 'index'])->name('tagihan.index');
// penyewaan
// Penyewaan (Dashboard Admin/User)
Route::get('/dashboard/penyewaan', [PenyewaanController::class , 'index'])->name('penyewaan.index');
Route::get('/dashboard/penyewaan/{id}', [PenyewaanController::class , 'show'])->name('penyewaan.show');
Route::get('/dashboard/penyewaan/{id}/keranjang', [PenyewaanController::class , 'keranjang'])->name('penyewaan.keranjang');
Route::get('/dashboard/penyewaan/{id}/invoice', [PenyewaanController::class , 'cetakInvoice'])->name('penyewaan.invoice');
Route::delete('/dashboard/penyewaan/{id}', [PenyewaanController::class , 'destroy'])->name('penyewaan.destroy');

// Keranjang Item
Route::delete('/dashboard/keranjang/{id}', [KeranjangController::class , 'destroy'])->name('keranjang.destroy');
Route::get('/pemesanan/{id}/edit', [FrontController::class , 'pemesanan'])->name('keranjang.edit')->middleware('auth');
Route::put('/dashboard/keranjang/{id}', [KeranjangController::class , 'update'])->name('keranjang.update');
Route::post('/dashboard/keranjang/{id}/ajukan-batal', [KeranjangController::class , 'ajukanBatal'])->name('keranjang.ajukan-batal');

// Pembatalan Admin
Route::get('/dashboard/pembatalan-admin', [PenyewaanAdminController::class , 'indexPembatalan'])->name('penyewaanAdmin.pembatalan');
Route::post('/dashboard/pembatalan-admin/{id}', [PenyewaanAdminController::class , 'prosesPembatalan'])->name('penyewaanAdmin.prosesPembatalan');
// profil
Route::get('/dashboard/profil', [ProfilController::class , 'index'])->name('profil.index');
Route::put('/dashboard/profil', [ProfilController::class , 'update'])->name('profil.update');

// sopir
Route::get('/dashboard/penugasan', [PenugasanController::class , 'index'])->name('penugasan.index');
Route::get('/dashboard/penugasan/{id}', [PenugasanController::class , 'show'])->name('penugasan.show');
Route::get('/dashboard/penugasan/{id}/invoice', [PenugasanController::class , 'cetakInvoice'])->name('penugasan.invoice');
Route::post('/dashboard/penugasan/{id}/upload-bukti', [PenugasanController::class , 'uploadBukti'])->name('penugasan.upload-bukti');

// Notifikasi
Route::get('/dashboard/notifikasi', [NotifikasiController::class , 'listAll'])->name('notifikasi.all');
Route::get('/api/notifikasi', [NotifikasiController::class , 'index'])->name('notifikasi.index');
Route::post('/api/notifikasi/{id}/read', [NotifikasiController::class , 'read'])->name('notifikasi.read');
Route::post('/api/notifikasi/read-all', [NotifikasiController::class , 'readAll'])->name('notifikasi.readAll');
Route::delete('/api/notifikasi/{id}', [NotifikasiController::class , 'destroy'])->name('notifikasi.destroy');
Route::delete('/api/notifikasi/delete-all', [NotifikasiController::class , 'destroyAll'])->name('notifikasi.destroyAll');