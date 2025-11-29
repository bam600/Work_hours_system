{{-- PG13 修正承認画面(管理者) --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '修正承認(管理者)') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/attendanceinfo.css') }}">
    <!-- ログイン認証されたときに表示されるheader用CSS -->
    <link rel="stylesheet" href="{{ asset('css/login_auth.css') }}">
@endsection

@section('header')
    @if (Auth::check() && Auth::user()->is_admin == "1")    
        <div class="header__links">
            <a class="link" href="{{ route('adminrequest.list') }}">勤怠一覧</a>
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
    <h2 class="label">❙ 勤怠詳細</h2>
</div>

<form action="{{ route('adminattendance.approve', ['attendance_correct_request_id' => $attendance->attendRequest->id]) }}" method="POST">
    @csrf
    @php
        $editable = true;
    @endphp
    

<input type="hidden" name="staff_id" value="{{ $attendance->staff_id }}">

<table class="listtable">
    <tr>
        <th class="list1">名前</th>
        <td class="attendancelist">{{ $attendance->staff->user_name }}</td>
    </tr>
    <tr>
        <th class="list2">日付</th>
        <td class="attendancelist">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('Y年m月d日')}}</td>
    </tr>
    <tr>
        <th class="list2">出勤・退勤</th>
        <td class = "clockinout">
            <span>{{ ($attendance->clock_in)->format('H:i') }}
                ～ 
                {{ ($attendance->clock_out)->format('H:i') }}</span>
        </td>
    </tr>


@foreach($attendance->breaks as $index => $break)
<tr>
    <th class="list2">休憩{{ $loop->iteration }}</th>
    <td class="clockinout" colspan="4">
            <span>{{ \Carbon\Carbon::parse($break->start_time)->format('H:i') }} ～ {{ \Carbon\Carbon::parse($break->end_time)->format('H:i') }}</span>
    </td>
</tr>
@endforeach

<tr>
    <th class="list3">備考</th>
    <td class="attendancelist">
            {{ $attendance->note ?? '（なし）' }}
    </td>
</tr>
</table>
    @if($attendance->attendRequest && $attendance->attendRequest->status === 'approved')
        <div class="button-cell">
            <button type="submit" class="btn--noclick">承認済み</span>
        </div>
    @else
        <div class="button-cell">
            <button type="submit" class="btn--primary">承認</button>
        </div>
    @endif
</form>
@endsection