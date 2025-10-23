<!-- HTML5文書であることを宣言 -->
<!DOCTYPE html>
<!-- ページ言語日本語に指定(ja=日本語) -->
<html lang="ja">
<!-- HTMLの表示設定などを定義するセクション(head) -->
<head>
    <!-- 文字コードをUTF-8に設定 -->
    <meta charset="UTF-8">
    <!-- モバイル対応のレスポンシブ設定 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ブラウザタブに表示されるページタイトル -->
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />

</head>
    <!-- ページの表示内容(本文の開始=>bodyタグ) -->
    <body>
        <header class="header">
            <div class="header__inner">
                <a class="header__logo" href="/">
<img src="{{ asset('images/logo.svg') }}" alt="ロゴ" class="class="logo-image">
                </a>
            </div>
        </header>
        <!-- 登録処理を行うルートにPOST送信するフォーム
        ルートは『register.store』 -->
        <form action="{{ route('goods.store') }}" method="post">
            <!-- laravelのセキュリティ上必須 (クロスサイトスクリプティング攻撃の保護-->
            @csrf
            <!-- フォームの見出し -->
            <h2>商品</h2>
 
            <button type="submit">登録する</button>
            <!-- ログイン画面へリンク　既存ユーザー向けの動線 -->
            <a href="{{ route('login') }}">ログインはこちら</a>
        </form>
    </body>
</html>