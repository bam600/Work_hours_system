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

<div class="register-wrapper">
    <div class="textback">
        <h2 class="label">{{ $statusLabel }}</h2>
    </div>

<!-- 今日の日付を取得 -->
<P class="today">{{ $now->isoFormat('YYYY年M月D日(ddd)') }}</P>
<!-- 今日の時間を取得 -->
<p class="time">{{ $now->setTimezone('Asia/Tokyo')->format('H:i') }}</p>

    @if(empty($todayAttendance))
    
        <form method="POST" action="{{ route('attendance.store') }}">
            @csrf
            <!-- status:checkin=出勤になる -->
            <button type="submit" name="status" value="checkin" class="btn--check">出勤</button>
        </form>

    @elseif($todayAttendance->status=='checkin')
    <button type="submit" name="checkout" class="btn--check">退勤</button>
    <button type="submit" name="break" class="btn--break">休憩入</button>
    @endif
    <!-- <button type="submit" name="endbreak" class="btn--break">休憩戻</button> -->

    <!-- <p>お疲れさまでした</p>
-->

</div>


@endsection