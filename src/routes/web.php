<?php
//! laravelのルーティング機能を使うための宣言(Route::get（）Route::post（）が使える)
use Illuminate\Support\Facades\Route;
//! 会員登録処理を担当するRegisterControllerを読み込むshow()やstore()メソッドが使える
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AdminSessionController;
use App\Http\Controllers\AdminController;

// PG01 会員登録画面**************************************************************************************
    //! get:会員登録フォームを表示(showは表示という責務が明確)
    Route::get('/register', [RegisterController::class, 'create'])->name('register'); 
    //! post: 会員登録フォームの送信処理（storeは保存という責務が明確）
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');


//pG02 ログイン画面(一般ユーザー)**************************************************************************
    // ログイン画面（GET /login）
    Route::get('/login',  [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest')->name('login');   // ← create に修正
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');

// PG03 勤怠登録画面**************************************************************************************
    Route::get('/attendance', [AttendanceController::class, 'create'])->middleware(['auth'])->name('attendance.create');

    Route::post('/attendance', [AttendanceController::class, 'store'])->middleware(['auth'])->name('attendance.store');



//pG07 ログイン画面(管理者)*************************************************************************
Route::middleware(['guest'])->group(function () {
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
});