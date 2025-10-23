
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
    <title>@yield('title', 'デフォルトタイトル')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/contents.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/product_img.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/header_title.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gorditas:wght@400;700&family=Noto+Sans+JP:wght@810&display=swap" rel="stylesheet">
    @yield('head')
    @livewireStyles
</head>
    <!-- ページの表示内容(本文の開始=>bodyタグ) -->
<body>  
<header class="header">
    <div class="header__inner">
        <a href="/" class="header__logo">
            <img class="logo-image" src="{{ asset('images/logo.svg') }}" alt="ロゴ">
        </a>

        @if(Auth::check() && !Request::is('register') && !Request::is('login'))
            <div class="flex">
                <form method="GET" action="{{ route('item.index') }}">
                    <input type="text" name="search" value="{{ $keyword ?? '' }}" placeholder="検索内容を入力してください" />
                </form>
            </div>

            <div class="header-actions">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="header_logout" type="submit">ログアウト</button>
                </form>
                <a class="header-link" href="{{ route('mypage') }}">マイページ</a>
                <a class="header-link header-button" href="{{ route('item.create') }}">出品する</a>
            </div>
        @endif
    </div>

    <main>
        @yield('content')
    </main>
@livewireScripts
</body>
</html>