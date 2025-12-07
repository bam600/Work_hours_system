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
        @if ($todayAttendance && $todayAttendance->status === 'checkout')
            {{-- 退勤済みのときだけ表示するヘッダー --}}
            <div class="header__links">
                <a class="link" href="{{ route('list.create') }}">今月の勤怠一覧</a>
                <a class="link" href="{{ route('request.list') }}">申請一覧</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btm">ログアウト</button>
                </form>
            </div>
        @else
            {{-- それ以外（出勤中・休憩中・未出勤）のときのヘッダー --}}
            <div class="header__links">
                <a class="link" href="{{ route('list.create') }}">勤怠一覧</a>
                <a class="link" href="{{ route('attendance.create') }}">勤怠</a>
                <a class="link" href="{{ route('request.list') }}">申請</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btm">ログアウト</button>
                </form>
            </div>
        @endif
    @endif
@endsection


@section('content')  

<div class="register-wrapper">
    <div class="textback">
        <h2 class="label">{{ $statusLabel }}</h2>
    </div>

<!-- 今日の日付を取得 -->
<P class="today">{{ $now->isoFormat('YYYY年M月D日(ddd)') }}</P>
<!-- 今日の時間を取得 -->
<p class="time">{{ $now->format('H:i') }}</p>


{{-- ステータス表示 --}}
@if($todayAttendance && $todayAttendance->status)
    <p style="display: none;">ステータス：{{ $statusLabel }}</p>
@else
    <p style="display: none;">ステータス：勤務外</p>
@endif

{{-- 出勤ボタン or 出勤済み表示 --}}
@if(!$todayAttendance)
    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf
        <button type="submit" name="status" value="checkin" class="btn--check">出勤</button>
    </form>
@else
    <p style="display: none;">本日はすでに出勤済みです（{{ $clockInTime }}）</p>
@endif

{{-- 出勤中 or 休憩戻り --}}
@if($todayAttendance && ($todayAttendance->status === 'checkin' || $todayAttendance->status === 'endbreak'))
    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf
        <button type="submit" name="status" value="checkout" class="btn--check">退勤</button>
        <button type="submit" name="status" value="break" class="btn--break">休憩入</button>

    </form>
@endif

{{-- 休憩中 --}}
@if($todayAttendance && $todayAttendance->status === 'break')
    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf
        <button type="submit" name="status" value="endbreak" class="btn--break">休憩戻</button>
    </form>
@endif

{{-- 退勤済み --}}
@if($todayAttendance && $todayAttendance->status === 'checkout')
    <div class="text--end">お疲れさまでした。</div>
@endif

@error('status')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

@endsection