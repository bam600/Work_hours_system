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
                <a class="link" href="{{route('adminrequest.list') }}">勤怠一覧</a>
                <a class="link" href="{{ route('stafflist') }}">スタッフ一覧</a>
                <a class="link" href="{{ route('request.list') }}">申請一覧</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btm">ログアウト</button>
                </form>
            </div>
    @endif
@endsection


@section('content')  

<div class="register-wrapper">
    @if(isset($date))
    <h2 class="label">❙ {{ $date->format('Y年m月d日') }}の一覧</h2>
@else
    <h2 class="label">❙ 日付未取得</h2>
@endif
</div>

@php
    $prevDate = $date->copy()->subDay()->format('Y-m-d');
    $nextDate = $date->copy()->addDay()->format('Y-m-d');
@endphp

<table class="monthtable">
    <tr>
        <th class="labelleft">
            <a href="{{ route('adminrequest.list', ['date' => $prevDate]) }}" class="labelleft">← 前日</a>
        </th>
        <th colspan="4" class="monthlabel">
            📅{{ $date->format('Y年m月d日') }}
        </th>
        <th class="labelright">
            <a href="{{ route('adminrequest.list', ['date' => $nextDate]) }}"" class="labelright">翌日→</a>
        </th>
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
    @if(isset($dailyRecords) && count($dailyRecords) > 0)
    @foreach($dailyRecords as $record)
        <tr class="find">
            <th class="listleft4">{{ $record['staff_name']}}</th>
            <th class="listleft4">{{ $record['clock_in']}}</th>
            <th class="listleft4">{{ $record['clock_out'] }}</th>
            <th class="listleft4">{{ $record['break_time'] }}</th>
            <th class="listleft4">{{ $record['work_time'] }}</th>
    @if (!empty($record['staff_id']))
    <th>
        <a class="infobtm" href="{{ route('adminattendance.info', ['id' => $record['id']]) }}?staff_id={{ $record['staff_id'] }}">詳細</a>
        
    </th>
    @else
        <th class="infobtm">詳細</th>
    @endif
        </tr>
    @endforeach
    @endif
</table>
@endsection