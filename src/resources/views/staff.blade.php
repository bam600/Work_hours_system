{{-- PG12 スタッフ別勤怠一覧画面（管理者） --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', 'スタッフ別勤怠') 

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
    <h2 class="label">❙{{ $staff->user_name }}さんの勤怠一覧</h2>
</div>

@php
    $prevMonth = $date->copy()->subMonth()->format('Y-m');
    $nextMonth = $date->copy()->addMonth()->format('Y-m');
@endphp

<table class="monthtable">
    <tr>
        <th class="labelleft">
            <a href="{{ route('staff.attendance', ['id' => $id, 'month' => $prevMonth]) }}" class="labelleft">←前月</a>
        </th>

        <th colspan="4"class="monthlabel">📅{{ $date->format('Y/m') }}</th>
        <th class="labelright">
            <a href="{{ route('staff.attendance', ['id' => $id, 'month' => $nextMonth]) }}" class="labelright">翌月→</a>
        </th>
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
            <th class="listleft4">{{ $record['actual_work_time'] }}</th>
        @if (!empty($record['id']))
            <th><a class="infobtm" href="{{ route('adminattendance.info', ['id' => $record['id']]) }}?staff_id={{ $record['staff_id'] }}">詳細</a></th>
        @else
            <th>詳細</th>
        @endif
        </tr>
    @endforeach
</table>
    <!-- CSV出力フォーム -->
    <form action="{{ route('staff.attendance.export', ['id' => $id]) }}" method="GET">
        <div class="btn-wrapper">
            <input type="hidden" name="month" value="{{ $date->format('Y-m') }}">
            <button type="submit" class="btn--primary">CSV出力</button>
        </div>
    </form>
@endsection