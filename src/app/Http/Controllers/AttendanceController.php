<?php
// - このクラスが属する名前空間は App\Http\Controllers
namespace App\Http\Controllers;

// - Auth：Laravelの認証ファサード。Auth::attempt() などでログイン処理を行う。
use Illuminate\Support\Facades\Auth;
// LoginRequest：バリデーションルールを定義したフォームリクエスト。loginstore() の引数で使用されます。
use Illuminate\Http\Request;
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

        // 勤怠ステータスの初期設定
        $statusLabel = '勤務外';

        // ステータスに変更があれば判定して上書き
        if ($todayAttendance && $todayAttendance->status !== null) {
            $labels = [
                'checkin' => '出勤中',
                'break' => '休憩中',
                'endbreak' => '出勤中',//休憩後の勤務再開
                'checkout' => '退勤',
            ];

    // statusに該当するlabelを取り出して格納。未定義だったら不明な状態を格納
    $statusLabel = $labels[$todayAttendance->status] ?? '不明な状態';
}

        // $todayAttendanceの情報を渡す。勤怠登録画面に遷移
        return view('attendance', compact('todayAttendance','now','statusLabel'));
    }

    // 今日の勤怠を保存
    public function store(Request $request)
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

        // ステータスの条件分岐
        switch ($status) {
            // 出勤
            case 'checkin':
                // Attendance新たなテーブルを作成
                Attendance::create([
                    'staff_id' => auth()->id(),
                    'status' => 'checkin',
                    'clock_in' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            break;
            
            // 休憩
            case 'break':
                // Attendanceモデルに今日の分のレコードが存在したら
                if ($todayAttendance) {
                    // 紐づくBreakモデルを呼び出し新しいレコードを作成
                    $todayAttendance->breaks()->create([
                        'start_time' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
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
                    // Attendanceテーブルの該当するレコードを更新
                    $todayAttendance->update([
                        'clock_out' => $now,
                        'status' => 'checkout',
                    ]);
            break; 
                }

        // ステータスに変更があれば判定して上書き
        if ($todayAttendance && $todayAttendance->status !== null) {
            $labels = [
                'checkin' => '出勤中',
                'break' => '休憩中',
                'endbreak' => '出勤中',//休憩後の勤務再開
                'checkout' => '退勤',
        ];
    }

    $statusLabel = $labels[$todayAttendance->status] ?? '不明な状態';
        
        // 登録後ルート名attendance(/attendance)に遷移
        return redirect()->route('attendance');
    }
        
}