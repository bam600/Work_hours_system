{{-- PG08 勤怠一覧画面(管理者) --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '勤怠一覧画面(管理者)') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/attendancelist.css') }}">
    <!-- ログイン認証されたときに表示されるheader用CSS -->
    <link rel="stylesheet" href="{{ asset('css/login_auth.css') }}">
@endsection

@section('header')
    @if (Auth::check())
            <div class="header__links">
                <a class="link" href="{{ route('list.create') }}">勤怠一覧</a>
                <a class="link" href="{{ route('attendance.create') }}">スタッフ一覧</a>
                <a class="link" href="{{ route('login') }}">申請一覧</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btm">ログアウト</button>
                </form>
            </div>
    @endif
@endsection


@section('content')  

<div class="register-wrapper">
    <h2 class="label">❙ {{ $date->format('Y年m月d日') }}の一覧</h2>
</div>

@php
    $prevMonth = $date->copy()->subMonth()->format('Y-m-d');
    $nextMonth = $date->copy()->addMonth()->format('Y-m-d');
@endphp

<table class="monthtable">
    <tr colspan="3">
    <a href="{{ route('list.create', ['month' => $prevMonth]) }}" class="labelleft">←前日</a>
    <th class="monthlabel">📅{{ $date->format('Y/m/d') }}</th>
    <a href="{{ route('list.create', ['month' => $nextMonth]) }}" class="labelright">翌日→</a>
    </tr>
</table>

<table class="listtable">
    <tr>
        <th class="listleft1">名前</th>
        <th class="listleft2">出勤</th>
        <th class="listleft2">退勤</th>
        <th class="listleft2">休憩</th>
        <th class="listleft2">合計</th>
        <th class="listleft3">詳細</th>
    </tr>

    <!-- 検索結果↓ -->
    @foreach($dailyRecords as $record)
        <tr class="find">
            <th class="listleft4">{{ $record['date'] }}（{{ $record['weekday'] }}）</th>
            <th class="listleft4">{{ $record['clock_in'] }}</th>
            <th class="listleft4">{{ $record['clock_out'] }}</th>
            <th class="listleft4">{{ $record['break_time'] }}</th>
            <th class="listleft4">{{ $record['work_time'] }}</th>
        @if (!empty($record['id']))
            <th><a href="{{ route('attendance.info', ['id' => $record['id']]) }}">詳細</a></th>
        @else
            <th>詳細</th>
        @endif
        </tr>
    @endforeach
</table>
@endsection