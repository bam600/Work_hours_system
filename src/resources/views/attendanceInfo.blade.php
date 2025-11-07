{{-- PG05 勤怠詳細画面 --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '勤怠詳細(承認待)') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/attendanceinfo.css') }}">
    <!-- ログイン認証されたときに表示されるheader用CSS -->
    <link rel="stylesheet" href="{{ asset('css/login_auth.css') }}">
@endsection

@section('header')
    @if (Auth::check())
            <div class="header__links">
                <a class="link" href="{{ route('attendance.create') }}">勤怠</a>
                <a class="link" href="{{ route('list.create') }}">勤怠一覧</a>
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
    <h2 class="label">❙ 勤怠詳細</h2>
</div>


<table class="listtable">
    <tr>
        <th colspan="1" class="list1">名前</th>
        <th class="attendancelist">{{ $attendance->staff->user_name }}</th>
    </tr>
    <tr>
        <th class="list2">日付</th>
        <td class="attendancelist">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('Y年')}}</td>
        <td>{{ \Carbon\Carbon::parse($attendance->clock_in)->format('m月d日')}}</td>
    </tr>
    <tr>
        <th class="list2">出勤・退勤</th>
        <th><td><input type="text" name="clockin" class="attendancelist" value = "{{ $attendance->clock_in }}"> ～ 
        <input type="text" name="clockout" class="attendancelist" value="{{ $attendance->clock_out }}"></td></th>
    </tr>

@foreach($attendance->breaks as $break)
    <tr>
        <th class="list2">休憩{{ $loop->iteration }}</th>
        <th>
            <td>
                <input type="text" name="break_start[]" class="attendancelist" value="{{ \Carbon\Carbon::parse($break->start_time)->format('H:i') }}">
                ～
                <input type="text" name="break_end[]" class="attendancelist" value="{{ \Carbon\Carbon::parse($break->end_time)->format('H:i') }}">
            </td>
        </th>
    </tr>
@endforeach

    <tr>
        <th class="list3">備考</th>
        <th><textarea name="textarea" cols="30" rows="3"></textarea></th>
    </tr>
</table>
    <form action="{{ route('register.store') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn--primary">修正</button>
    </form>
@endsection