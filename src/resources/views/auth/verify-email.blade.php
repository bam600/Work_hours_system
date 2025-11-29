{{-- mail認証画面 --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', 'メール確認')

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/mailsend.css') }}">
@endsection

{{--以下会員登録フォーム--}}
@section('content')
<div class="verify-wrapper">
    <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p>メール認証を完了してください。</p>

    <form method="GET" action="{{ route('attendance.create') }}">
    <button type="submit" class="btn--primary">認証はこちらから</button>
    </form>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="center-link">認証メールを再送する</button>
    </form>
</div>
@endsection