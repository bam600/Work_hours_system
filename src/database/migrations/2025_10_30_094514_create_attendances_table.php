<?php
// attendances(勤怠時間管理テーブル)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            //staff_idの外部キー設定
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            // 出勤時間
            $table->timestamp("clock_in")->nullable();;
            // 退勤時間
            $table->timestamp("clock_out")->nullable();;
            // 申請画面の備考欄
            $table->text('note')->nullable();
            // 勤怠時間
            $table->time("actual_work_time")->nullable();
            // 勤怠ステータス
            $table->String("status",20);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
