<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

Route::match(['get', 'post'], '/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Redirect Halaman Utama ke Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// SEMUA ROUTE UTAMA (Membutuhkan Login)
Route::middleware(['auth'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard-admin', function () {
        return view('admin.dashboard');
    })->name('dashboard.admin');

    // 2. Route Pencarian Buku (Soal Nomor 1, A2)
    Route::get('/buku-cari', function (Request $request) {
        $judul = $request->query('judul');
        $buku = DB::select("SELECT * FROM bukus WHERE judul = ?", [$judul]);
        return response()->json($buku);
    })->name('buku.cari');

    // 3. Route Koleksi Buku (Umum)
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

    // 4. Route Kelola Buku (Soal 3: Diproteksi Gate kelola-buku)
    Route::middleware(['can:kelola-buku'])->group(function () {
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [\App\Http\Controllers\Api\BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
        Route::put('/buku/{buku}', [\App\Http\Controllers\Api\BukuController::class, 'update'])->name('buku.update');

    // Rute penghapusan menggunakan GET sehingga bisa dipicu lewat tautan luar
//     Route::get('/buku/{buku}/hapus', [BukuController::class, 'destroy'])->name('buku.hapus.get');
// });
        Route::delete('/buku/{buku}', [\App\Http\Controllers\Api\BukuController::class, 'destroy'])->name('buku.destroy');
        // // ROUTE CELAH A3: Menghapus buku via GET
        // Route::get('/buku/{buku}/hapus', [BukuController::class, 'destroy']);
    });

    // 5. Route Show Buku (Harus ditaruh di bawah /buku/create agar tidak bentrok)
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

Route::middleware(['auth'])->group(function () {

    // 1. Dashboard, Buku, dll...

    // 2. Peminjaman (Semua di dalam grup auth)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::patch('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'pengembalian'])->name('peminjaman.kembali');
    
    // ROUTE DETAIL PEMINJAMAN (HANYA DITULIS 1 KALI DI SINI)
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatSaya'])->name('peminjaman.riwayat');
    Route::get('/riwayat-peminjaman/{id}', [PeminjamanController::class, 'riwayatBerdasarkanId'])->name('peminjaman.riwayat.id');
});

    // 7. Laporan & Persetujuan (Admin & Petugas)
    Route::middleware(['role:admin|petugas'])->group(function () {
        Route::get('/laporan', [PeminjamanController::class, 'laporan'])->name('laporan.index');
        Route::patch('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::patch('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    });

    // 8. Kelola Data Pengguna (Khusus Admin)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 9. Profile Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

}); // Penutup Route::middleware(['auth'])

// Fallback endpoint API untuk Vercel (tanpa mengubah nama route lain)
Route::get('/kategori', function () {
    return response()->json(
        \App\Models\Kategori::query()
            ->selectRaw('MIN(id) as id, nama')
            ->groupBy('nama')
            ->orderBy('nama')
            ->get()
    );
});

Route::get('/tes-error', function () {
    abort(500, 'Tes Error Aplikasi');
});

require __DIR__.'/auth.php';