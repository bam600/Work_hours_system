<?php
// - このクラスが属する名前空間は App\Http\Controllers
namespace App\Http\Controllers;

// - Auth：Laravelの認証ファサード。Auth::attempt() などでログイン処理を行う。
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRequest;
use Illuminate\Http\Request;
use App\Models\BreakModel;
use App\Models\Attendance;
use Carbon\Carbon;


//勤怠管理画面のcontroller
class AttendanceController extends Controller
{   
    // 今日の出勤check
    public function create()
    {   
        // 今の日時を取得
        $now = Carbon::now()->setTimezone('Asia/Tokyo');

        // Attendanceモデルからログイン中のstaff_idを取得
        $todayAttendance = Attendance::where('staff_id', auth()->id())
            // 更に今日の日付の勤怠記録を探す
            ->whereDate('created_at', Carbon::today())
            // 最初の1件だけ取得
            ->first();

        // clock_in を Carbon に変換（ビューで使うならここで整形）
        $clockInTime = $todayAttendance ? \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') : null;


        $statusLabel = $todayAttendance ? $this->getStatusLabel($todayAttendance->status) : '勤務外';

        // 管理者か否か判定
        $isAdmin = Auth::user()->is_admin;

        // $todayAttendanceの情報を渡す。勤怠登録画面に遷移
        return view('attendance', compact('todayAttendance','now','statusLabel','clockInTime','isAdmin')); 
    }

    // 今日の勤怠を保存
    public function store(AttendanceRequest $request)
    {   
        // 勤怠ステータスの保存
        $status = $request->input('status'); // 'checkin' など

        // ボタン押下時の日時
        $now = Carbon::now()->setTimezone('Asia/Tokyo');

        // Attendanceモデルからログイン中のstaff_idを取得
        $todayAttendance = Attendance::where('staff_id', auth()->id())
        // 更に今日の日付の勤怠記録を探す
        ->whereDate('created_at', $now->toDateString())
        // 最初の1件だけ取得
        ->first();
        

        if (!$todayAttendance && $status !== 'checkin') {
            return redirect()->route('attendance.create')->withErrors('勤怠記録が見つかりません。');
    }

        // ステータスの条件分岐
        switch ($status) {
            // 出勤
            case 'checkin':
                // 出勤の重複check
                if ($todayAttendance) {
                    return redirect()->route('attendance.create')->withErrors('本日の出勤記録はすでに存在します。');
                }
                // Attendance新たなテーブルを作成
                Attendance::create([
                    'staff_id' => auth()->id(),
                    'status' => 'checkin',
                    'clock_in' => $now,
                ]);
            break;
            
            // 休憩
            case 'break':
                // Attendanceモデルに今日の分のレコードが存在したら
                // 紐づくBreakモデルを呼び出し新しいレコードを作成
                if ($todayAttendance) {
                    BreakModel::create([
                        'attendance_id' => $todayAttendance->id,
                        'start_time' => $now,
                    ]);
                    
                        // Attendanceモデルの該当するレコードのステータスを更新
                        $todayAttendance->update([
                        'status' => 'break',
                        ]);
                    }
            break; 

            // 休憩戻り出勤中
            case 'endbreak':
                // 勤怠レコード(todayAttendance)に紐づく休憩レコードを最新1件取得
                $latestBreak = $todayAttendance->breaks()->latest()->first();

                // まだ終了していない最新の休憩レコードに終了時間を記録
                if ($latestBreak && !$latestBreak->end_time){
                        $latestBreak->update([
                            'end_time' => $now,
                        ]);
                        
                        // Attendanceテーブルの該当するレコードを更新
                        $todayAttendance->update([
                            'status' => 'endbreak',
                        ]);
                }
            break;

            // 退勤
            case 'checkout':
                // 休憩中の場合退勤できない
                if ($todayAttendance->status === 'break') {
                    return redirect()->route('attendance.create')
                    ->withErrors('先に休憩を終了してください');
                }
                    // Attendanceテーブルの該当するレコードを更新
                    $todayAttendance->update([
                        'clock_out' => $now,
                        'status' => 'checkout',
                    ]);
            break; 
                }
        // 登録後ルート名attendance(/attendance)に遷移
        return redirect()->route('attendance.create');

    }

//statusに入力があったら該当するラベルに書き換える 
private function getStatusLabel($status)
    {
        $labels = [
            'checkin' => '出勤中',
            'break' => '休憩中',
            'endbreak' => '出勤中',
            'checkout' => '退勤済',
        ];

        return $labels[$status] ?? '不明な状態';
    }
}