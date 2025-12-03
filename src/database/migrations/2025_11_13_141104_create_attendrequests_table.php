<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('attendrequests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id'); // 勤怠データとの紐付け
            $table->unsignedBigInteger('staff_id');      // 申請者
            $table->string('status')->default('pending'); // 申請状態
            $table->unsignedBigInteger('approved_by')->nullable(); // 承認者
            $table->timestamp('approved_at')->nullable(); // 承認日時
            $table->timestamps();

            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('restrict');
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('restrict');
            $table->foreign('approved_by')->references('id')->on('staffs')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendrequests');
    }
};