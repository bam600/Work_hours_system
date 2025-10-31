<?php
// breaks(休憩時間管理テーブル)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // breaks(休憩)テーブルの作成
        Schema::create('breaks', function (Blueprint $table) {
            // id
            $table->id();
            // attendance_idの外部キー設定
            $table->foreignId('attendance_id')->constrained('attendances')->onDelete('cascade');
            //休憩開始
            $table->timestamp("start_time")->nullable();;
            // 休憩終了
            $table->timestamp("end_time")->nullable();;
            // 休憩の合計時間
            $table->time("actual_break_time")->nullable();;
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
        Schema::dropIfExists('breaks');
    }
}
