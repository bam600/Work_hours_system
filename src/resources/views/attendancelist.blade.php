{{-- PG04 勤怠一覧画面(一般) --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '勤怠一覧画面') 

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
                <a class="link" href="{{ route('login') }}">勤怠</a>
                <a class="link" href="{{ route('login') }}">申請</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btm">ログアウト</button>
                </form>
            </div>
    @endif
@endsection


@section('content')  

<div class="register-wrapper">
    <h2 class="label">❙ 勤怠一覧</h2>
</div>

<table class="monthtable">
    <tr colspan="3">
        <th class="labelleft">←先月</th>
        <th class="monthlabel">📅{{ $date->format('Y/m') }}</th>
        <th class="laberight" >翌月→</th>
    </tr>
</table>

<table class="listtable">
    <tr>
        <th class="listleft1">日付</th>
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
        <th class="listleft4">詳細</th>
    </tr>
    @endforeach
</table>


@endsection