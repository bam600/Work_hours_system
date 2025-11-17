{{-- PG01 会員登録画面 --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '会員登録画面') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/user-access.css') }}">
@endsection

{{--以下会員登録フォーム--}}
@section('content')  

{{--登録を行うPOSTの送信先に、RegisterControllerのstoreメソッド(register.store)を使用。--}}
<form action="{{ route('register.store') }}" method="POST">
    {{--@csrf はLaravelのセキュリティ機能（CSRF対策--}}
    @csrf

<div class="register-wrapper">
    <h2 class="form-title">会員登録</h2>

<div class="form-group">
    <label for="user_name">名前</label>

    {{--type="text" → テキストを入力できるボックスを作る--}}
    {{--id="user_name" → ページ内で一意の名前。CSSやJavaScriptで使う。--}}
    {{--name="user_name" → フォームを送信するときに、サーバーへ送るデータのキー（Laravelでは、コントローラ側で $request->user_name で受け取れる）--}}
    {{--{{ old('user_name') }}が表示されるoldは直前の入力内容を一時的に記憶--}}
    <input 
        type="text" 
        id="user_name" 
        name="user_name" 
        value="{{ old('user_name') }}">
    {{--@error('user_name')はバリデーションエラー時にエラーメッセージを表示(user_nameはname属性を指す--}}
    @error('user_name') 
        <span class="error">
            {{ $message }}
        </span>
    @enderror
</div>

<div class="form-group">
    <label for="email">メールアドレス</label>
        <input type="text"
                id="email"
                name="email"
                value="{{ old('email') }}">
    @error('email')
        <span class="error">
            {{ $message }}
        </span>
    @enderror
</div>

<div class="form-group">
    <label for="password">パスワード</label>
    <input type="password"
            id="password" 
            name="password">
    @error('password')
        <span class="error">
            {{ $message }}
        </span>
    @enderror
</div>

<div class="form-group">
    <label for="password_confirmation">確認用パスワード</label>
        <input type="password" 
                id="password_confirmation" 
                name="password_confirmation">
    @error('password_confirmation') 
        <span class="error">
            {{ $message }}
        </span>
    @enderror
</div>

    {{-- button は、ボタンを作るHTMLタグ type="submit" は「フォームを送信するボタン ボタンを押すと、name="" 属性で指定されたデータがサーバー（Laravelコントローラ）に送信 --}}
    <button type="submit" class="btn btn--primary">登録する</button>
        <div class="center-link">

            {{--ルートヘルパー関数で /loginのURLを作成しクリックすると遷移--}}
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
</div>
</form>
@endsection