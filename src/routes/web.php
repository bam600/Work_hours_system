<?php
//! laravelのルーティング機能を使うための宣言(Route::get（）Route::post（）が使える)
use Illuminate\Support\Facades\Route;
//! 会員登録処理を担当するRegisterControllerを読み込むshow()やstore()メソッドが使える
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AdminSessionController;
use App\Http\Controllers\RequestListController;
use App\Http\Controllers\AttendanceInfoController;
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

// PG04 勤怠一覧画面**************************************************************************************
    Route::get('/attendance/list', [AttendanceListController::class, 'create'])->middleware(['auth'])->name('list.create');
    Route::post('/attendance/list',[AttendanceListController::class, 'store'])->middleware(['auth'])->name('attendancelist.store');

// PG05 申請詳細画面**************************************************************************************
    Route::get('/attendance/detail/{id}', [AttendanceInfoController::class, 'show'])->middleware(['auth'])->name('attendance.info');

    Route::post('/attendance/detail/{id}', [AttendanceInfoController::class, 'update'])->middleware(['auth'])->name('attendance.update');

// PG06 申請一覧画面**************************************************************************************
    Route::get('/stamp_correction_request/list', [RequestListController::class, 'create'])->middleware(['auth'])->name('request.list');

//pG07 ログイン画面(管理者)*************************************************************************
Route::middleware(['guest'])->group(function () {
    Route::get('/admin/login', [AdminController::class,'showLoginForm'])->name('adminlogin');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->name('admin.store');
});
//pG08 勤怠一覧画面(管理者)*************************************************************************
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/attendance/list', [AdminController::class,'attendancelist'])->name('attendance.list');
});
