<?php

/**Migration クラスをインポート
  *laravelのマイグレーション機能の基盤となるクラスで
  up()とdown()メソッドを定義*/
  use Illuminate\Database\Migrations\Migration;
/**Blueprintクラスをインポート。
  テーブル構造（カラム定義など）を記述するためのクラス*/
  use Illuminate\Database\Schema\Blueprint;
/**Schemaファサードをインポート。
  テーブルの作成・削除など、スキーマ操作を行うためのインターフェース*/
use Illuminate\Support\Facades\Schema;

/**マイグレーションクラスの定義
  *クラス名はCreate[テーブル名]Tableにした方がいい
  Migrationを継承することで、Laravelのマイグレーションとして機能*/
class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //マイグレーション実行時に呼ばれるメソッド
    //php artisan migrate 実行時にこのメソッドが呼ばれ、テーブルが作成される
    public function up()
    {
        //profiles:テーブルを新規作成する命令
        //Blueprint $table によって、テーブルの構造を定義する準備が整う
        Schema::create('profiles', function (Blueprint $table) {
        
        $table->id();

        /** 
          *foreignId('user_id')→user_idカラムを作成
          *型はBIGINTUNSIGNED（Laravelの id() と同じ型）
          *Laravelはこのメソッドで外部キーとして使う為のID
          *constrained('users')user_idをusersテーブルのidカラムと
          *紐づける外部キー制約を追加。引数を省略すると、自動的にuser_id→
          *users.idを推測する
          *onDelete('cascade')→関連するユーザが削除された場合、このレコードも
          *一緒に削除される(カスケード削除)のように設定
        **/  
        $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
        

        // /**ユーザ名カラム */
        // $table->string('user_name', 255);
        /**郵便番号*/
        $table->string('postal_code', 8);
        /**住所 */
        $table->string('address', 255);
        /**建物名(nullok) */
        $table->string('building', 255)->nullable();
        /**レコード作成日時と更新日時を記録するためのカラム */
        $table->timestamps(); // created_at, updated_at
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        /**もしprofilesテーブルが存在していれば削除するという命令
         * dropIfExists() は安全策として使われる
         * テーブルが存在しない場合でもエラーにならないようにしてくれる。
         * down() メソッドはup()メソッドで作成された
         * profiles テーブルを削除するためのもの
         * php artisan migrate:rollback を実行すると、この down() が呼ばれ
         * テーブルが削除される
         **/
        Schema::dropIfExists('profiles');
    }
}
