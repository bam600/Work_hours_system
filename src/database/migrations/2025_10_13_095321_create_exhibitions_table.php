<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExhibitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 出品商品テーブル
        Schema::create('exhibitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('出品者ID（所有者責務）');
            $table->string('product_name', 255)->comment('商品名（表示責務）');
            $table->string('brand', 255)->nullable()->comment('ブランド名（任意属性責務）');
            $table->unsignedBigInteger('condition_id')->comment('商品状態ID（状態責務）');
            $table->text('description')->comment('商品説明（内容責務）');
            $table->integer('price')->comment('価格（金額責務）');
            $table->string('status', 20)->default('published')->comment('出品状態（draft:下書き, published:販売中,sold:売り切れ）'); // 状態管理責務
            $table->string('img', 255)->nullable()->comment('商品画像（任意属性責務）');
            $table->timestamps(); // 登録・更新日時（監査責務）

            // 外部キー制約（責務連携）
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        }); // ← クロージャを閉じる
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exhibitions');
    }
}
