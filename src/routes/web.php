<?php
//! laravelのルーティング機能を使うための宣言(Route::get（）Route::post（）が使える)
use Illuminate\Support\Facades\Route;
//! 会員登録処理を担当するRegisterControllerを読み込むshow()やstore()メソッドが使える
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AdminSessionController;
use App\Http\Controllers\RequestListController;
use App\Http\Controllers\AttendanceInfoController;
use App\Http\Controllers\AdminAttendanceListController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffListController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminAttendanceInfoController;
use App\Http\Controllers\AdminApproveController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;


// PG01 会員登録画面**************************************************************************************
    //! get:会員登録フォームを表示(showは表示という責務が明確)
    Route::get('/register', [RegisterController::class, 'create'])->name('register'); 
    //! post: 会員登録フォームの送信処理（storeは保存という責務が明確）
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
// メール認証**************************************************************************************
// メール認証通知画面
Route::get('/email/verify-alt', function () {
    return view('auth.verify'); // 別名ルートでも同じビュー
})->middleware('auth')->name('verification.notice.alt');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    // 認証を完了させる
    $request->fulfill();

// 認証完了後に完了メールを送信
    Mail::to($request->user()->email)->send(new WelcomeMail());

// 勤怠画面へ遷移
    return redirect('/attendance');
})->middleware(['auth', 'signed'])->name('verification.verify');

// 認証メール再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '確認メールを再送しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

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
    Route::post('/attendance/detail/{id}', [AttendanceInfoController::class, 'submit'])->middleware(['auth'])->name('attendance.submit');


// PG06 申請一覧画面**************************************************************************************
    Route::get('/stamp_correction_request/list', [RequestListController::class, 'create'])->middleware(['auth'])->name('request.list');

//pG07 ログイン画面(管理者)*************************************************************************
Route::middleware(['guest'])->group(function () {
    Route::get('/admin/login', [AdminController::class,'showLoginForm'])->name('adminlogin');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->name('admin.store');
});
//pG08 勤怠一覧画面(管理者)*************************************************************************
    Route::get('/admin/attendance/list', [AdminAttendanceListController::class,'adminrequestlist'])->middleware('auth')->name('adminrequest.list');

//pG09 勤怠詳細覧画面(管理者)*************************************************************************
    Route::get('admin/attendance/{id}', [AdminAttendanceInfoController::class,'show'])->middleware('auth')->name('adminattendance.info');
    Route::post('admin/attendance/{id}', [AdminAttendanceInfoController::class,'submit'])->middleware('auth')->name('adminattendance.show');
    
// PG10 スタッフ一覧画面(管理者)**************************************************************************************
    Route::get('/admin/staff/list', [StaffListController::class, 'show'])->middleware(['auth'])->name('stafflist');
    // CSV出力用
    

// PG12 スタッフ別勤怠一覧画面(管理者)**************************************************************************************
    Route::get('/admin/attendance/staff/{id}/{month?}', [StaffController::class, 'show'])->middleware(['auth'])->name('staff.attendance');
    // CSV出力
    Route::get('/staff/{id}/attendance/export', [StaffController::class, 'exportCsv'])
    ->name('staff.attendance.export');

//pG13 修正承認詳細(管理者)*************************************************************************
    Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminApproveController::class,'show'])->middleware('auth')->name('adminattendance.show');

    Route::post('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminApproveController::class,'approve'])->middleware('auth')->name('adminattendance.approve');

    // テスト用
    Route::get('/', function () {return view('welcome');});
