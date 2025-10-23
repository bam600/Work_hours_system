
<?php
// 商品conditionテーブル
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->id(); //主キー
            $table->string('code', 50)->unique()->comment('状態コード: Good, Excellent, Fair, Poor'); //状態を英語
            $table->string('label',100)->comment('表示名: 良好、目立った傷や汚れなし, やや傷や汚れあり, 状態が悪い');  //状態を日本語
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
        Schema::dropIfExists('conditions');
    }
}
