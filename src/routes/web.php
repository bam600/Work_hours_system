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

// PG01 会員登録画面**************************************************************************************
    //! get:会員登録フォームを表示(showは表示という責務が明確)
    Route::get('/register', [RegisterController::class, 'show'])->name('register'); 
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

//pG07 ログイン画面(管理者)**************************************************************************
// ログイン画面（GET /login）
Route::get('admin/login',  [AdminSessionController::class, 'create'])
    ->middleware('guest')->name('login');   // ← create に修正
Route::post('admin/login', [AdminSessionController::class, 'store'])
    ->middleware('guest');
Route::post('/logout', [AdminSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');


// PG03 勤怠登録画面**************************************************************************************
    Route::get('/attendance', [AuthController::class, 'attendance'])->name('attendance');