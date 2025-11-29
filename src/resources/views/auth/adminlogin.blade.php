{{-- PG07 ログイン画面(管理者) --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', 'ログイン(管理者)') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/user-access.css') }}">
@endsection

{{--以下会員登録フォーム--}}
@section('content') 

<div class="register-wrapper">
    <h2 class="form-title">管理者ログイン</h2>

{{-- 成功/情報メッセージ（パスワードリセット送信後など） --}}
    @if (session('status'))
        <div class="alert info">{{ session('status') }}</div>
    @endif

<form method="POST" action="{{ route('admin.store') }}">
    @csrf
    <div class="form-group">
        <label for="email">メールアドレス</label>
        <input type="text" id="email" name="email" value="{{ old('email') }}"/>
    {{-- 入力欄の直後にエラーメッセージを置く --}}
    @error('email')
        <span class="error">{{ $message }}</span>
    @enderror
    </div>

    <div class="form-group">
        <label for="password">パスワード</label>
        <input type="password" id="password" name="password"/>
    @error('password') <span class="error">{{ $message }}</span> @enderror
</div>
    
    <button type="submit" class="btn btn--primary">管理者ログインする</button>
</form>

        <div class="center-link">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>

</div>
@endsection