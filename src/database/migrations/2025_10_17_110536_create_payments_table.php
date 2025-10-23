<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/**購入画面の支払い選択用テーブル */
class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {

        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // 1: カード払い, 2: コンビニ払い
            $table->string('name'); // 表示名
            $table->string('code'); // 内部コード
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

