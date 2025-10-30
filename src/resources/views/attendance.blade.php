{{-- PG03 勤怠登録画面 --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '勤怠画面') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
    <!-- ログイン認証されたときに表示されるheader用CSS -->
    <link rel="stylesheet" href="{{ asset('css/login_auth.css') }}">
@endsection

@section('header')
    @if (Auth::check())
        <div class="header__links">
            <a class="link" href="{{ route('login') }}">勤怠一覧</a>
            <a class="link" href="{{ route('login') }}">勤怠</a>
            <a class="link" href="{{ route('login') }}">申請</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btm">ログアウト</button>
            </form>
        </div>
    @endif
@endsection


{{--以下会員登録フォーム--}}
@section('content')  

{{--登録を行うPOSTの送信先に、名前付きルート register.store を使用。--}}
<form action="{{ route('register.store') }}" method="POST">
    @csrf

<div class="register-wrapper">
    <div class="textback">
        <h2 class="lavel">勤怠外</h2>
    </div>

@php
    use Carbon\Carbon;
    $today = Carbon::now();
@endphp

<P class="today">{{ \Carbon\Carbon::now()->isoFormat('YYYY年M月D日(ddd)') }}</P>
<p class="time">{{ \Carbon\Carbon::now()->setTimezone('Asia/Tokyo')->format('H:i') }}</p>

    <button type="submit" class="btn--check">出勤</button>

    <button type="submit" class="btn--check">退勤</button>
    <button type="submit" class="btn--break">休憩入</button>

    <button type="submit" class="btn--break">休憩戻</button>

    <button type="submit" class="btn--end">お疲れさまでした</button>

</div>


@endsection