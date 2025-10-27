<!-- 共通レイアウト -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'デフォルトタイトル')</title>

    <link rel="stylesheet" href="{{ asset('css/header_title.css') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">

    @yield('head')
    
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="/" class="header__logo" aria-label="ホームへ戻る">
                <img class="logo-image" src="{{ asset('images/logo.svg') }}" alt="ロゴ">
            </a>
        </div>
        @if(Auth::guard('admin')->check())
            <a href="{{ route('register') }}" style="color: white; background-color: black; padding: 5px;">勤怠一覧</a>
            <a href="{{ route('register') }}" style="color: white; background-color: black; padding: 5px;">スタッフ一覧</a>
            <a href="{{ route('register') }}" style="color: white; background-color: black; padding: 5px;">申請一覧</a>
            <a href="{{ route('register') }}" style="color: white; background-color: black; padding: 5px;">ログアウト</a>
        @else
            <p>ログイン中です！</p>
            <a href="{{ route('register') }}" style="color: white; background-color: black; padding: 5px;">勤怠</a>
            <a href="{{ route('register') }}" style="color: white; background-color: black; padding: 5px;">勤怠一覧」</a>
            <a href="{{ route('register') }}" style="color: white; background-color: black; padding: 5px;">申請</a>
            <a href="{{ route('register') }}" style="color: white; background-color: black; padding: 5px;">ログアウト</a>
        @endif

    </header>

    <main class="main">
        @yield('content')
    </main>
</body>
</html>