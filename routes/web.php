<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KonfirmasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanKehilanganController;
use App\Http\Controllers\LaporanPenemuanController;


use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



///halaman utama

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [UserController::class, 'dashboard'])->name('home');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', function () {
    if (auth()->user()->role == 'admin') {
        return redirect('/admin/dashboard');
    }

    return redirect('/user/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';


// ======================================================================

// LOGIN ADMIN
Route::get('/login-admin', function () {
    return view('auth.login');
})->middleware('guest')->name('login.admin');

// LOGIN USER
Route::get('/login-user', function () {
    return view('auth.login-user');
})->middleware('guest')->name('login.user');


// =============================================

// Dashboard admin dan user

// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->middleware('auth');

// Route::get('/user/dashboard', function () {
//     return view('user.dashboard');
// })->middleware('auth');





// Dashboard admin dan user

// DITAMBAHKAN / DIUBAH:
// Dashboard admin diarahkan ke AdminController agar bisa mengirim data
// seperti $kabupatens dan $laporan ke view admin.dashboard.
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware('auth');

// DITAMBAHKAN / DIUBAH:
// Dashboard user diarahkan ke UserController agar bisa mengirim data
// seperti $kabupatens dan $laporan ke view user.dashboard.
// Route::get('/user/dashboard', [UserController::class, 'dashboard'])
//     ->middleware('auth');


Route::get('/user/dashboard', [UserController::class, 'dashboard'])
    ->name('user.dashboard');

// ======================
// About admin

Route::get('/admin/about', function () {
    return view('admin.about');
})->middleware('auth');

// About User
// Route::get('/user/about', function () {
//     return view('user.about');
// })->middleware('auth');

Route::get('/user/about', function () {
    return view('user.about');
})->name('about');
// ===================================
//Dfatar admin
Route::get('/admin/daftar', [AdminController::class, 'daftar'])->middleware('auth');

//detail acc admin
Route::get('/admin/detail-acc/{type}/{id}', [AdminController::class, 'detailAcc']);

//detail admin
Route::get('/admin/detail/{id}', [AdminController::class, 'detail']);

//Konfirmasi Admin
Route::get('/admin/konfirmasi', [AdminController::class, 'konfirmasi']);
Route::get('/admin/konfirmasi/acc/{id}', [AdminController::class, 'accKonfirmasi']);
Route::get('/admin/konfirmasi/tolak/{id}', [AdminController::class, 'tolakKonfirmasi']);


//Vwrifikasi Admin

Route::get('/admin/verifikasi', [AdminController::class, 'verifikasi']);
Route::get('/admin/terima/{id}', [AdminController::class, 'terima']);
Route::get('/admin/tolak/{id}', [AdminController::class, 'tolak']);




//user

//detail-acc user
// Route::get('/user/detail-acc/{type}/{id}', [UserController::class, 'detailAcc'])->middleware('auth');

Route::get('/user/detail-acc/{type}/{id}', [UserController::class, 'detailAcc'])
    ->name('user.detail');

//form kehilangan
Route::get('/user/kehilangan', [LaporanKehilanganController::class, 'create'])
    ->middleware('auth');
    

// =========    

    Route::get('/user/profile', function () {

        $user = auth()->user();
    
        $kehilangan = \App\Models\LaporanKehilangan::where('user_id', $user->id)->get();
        $penemuan   = \App\Models\LaporanPenemuan::where('user_id', $user->id)->get();
        $pending    = \App\Models\LaporanPending::where('user_id', $user->id)->get();
    
        return view('user.profile', compact('user', 'kehilangan', 'penemuan', 'pending'));
    
    })->middleware('auth');


// ===
Route::post('/kehilangan/store', [LaporanKehilanganController::class, 'store']);


// ===

Route::get('/kehilangan/create', [LaporanKehilanganController::class, 'create']);
Route::get('/get-kecamatan/{kabupaten_id}', [LaporanKehilanganController::class, 'getKecamatan']);


//konfirmasi user
Route::get('/user/konfirmasi/{type}/{id}', [KonfirmasiController::class, 'form']);
Route::post('/user/konfirmasi', [KonfirmasiController::class, 'store']);



//penemuan user
Route::get('/user/penemuan', function () {
    return view('user.penemuan');
})->middleware('auth');

//penemuan user
Route::get('/user/penemuan', [LaporanPenemuanController::class, 'create'])
    ->middleware('auth');

Route::post('/penemuan/store', [LaporanPenemuanController::class, 'store'])
    ->middleware('auth');



//profile user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//Chat
use App\Http\Controllers\ChatController;

Route::middleware('auth')->group(function () {
    Route::get('/chat/{id}', [ChatController::class, 'index']);
    Route::post('/chat/send', [ChatController::class, 'send']);
    Route::get('/chat/messages/{id}', [ChatController::class, 'fetch']);
});



//google

#-----
// redirect ke google
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

// callback dari google
Route::get('/auth/google/callback', function () {

    $googleUser = Socialite::driver('google')->user();

    // cek user
    $user = User::where('email', $googleUser->getEmail())->first();

    if (!$user) {
        // register otomatis
        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'password' => bcrypt('random123'),
        ]);
    }

    Auth::login($user);

    return redirect('/user/dashboard');
});


//hapus
Route::delete('/admin/hapus/{type}/{id}', [AdminController::class, 'hapus'])
    ->middleware('auth')
    ->name('admin.hapus');


    //tandai ditemukan admin
    Route::get('/admin/status/{type}/{id}', [AdminController::class, 'ubahStatus'])
    ->name('admin.status');



    //status user
    Route::get('/user/status/{type}/{id}', [UserController::class, 'ubahStatus'])
    ->middleware('auth')
    ->name('user.status');

    //30 hari
    Route::get('/user/laporan-saya', [UserController::class, 'laporanSaya'])
    ->name('user.laporan-saya');

    Route::post('/user/kirim-ulang/{type}/{id}', [UserController::class, 'kirimUlang'])
    ->name('user.kirim-ulang');

    Route::get('/detail/{type}/{id}', [UserController::class, 'detail'])
    ->name('detail');
    