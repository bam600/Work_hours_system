{{-- PG09 勤怠詳細画面(管理者) --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '勤怠詳細(管理者)') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/attendanceinfo.css') }}">
    <!-- ログイン認証されたときに表示されるheader用CSS -->
    <link rel="stylesheet" href="{{ asset('css/login_auth.css') }}">
@endsection

@section('header')
    @if (Auth::check() && Auth::user()->is_admin == "1")    
        <div class="header__links">
            <a class="link" href="{{ route('attendance.create') }}">勤怠</a>
            <a class="link" href="{{ route('list.create') }}">勤怠一覧</a>
            <a class="link" href="{{ route('request.list') }}">申請</a>
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

<form action="{{ route('attendance.submit', ['id' => $attendance->id]) }}" method="POST">
    @csrf
    @php
        $editable = $editable ?? true;
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
            @if($editable)
                <input type="text" name="clockin" value="{{ old('clockin', ($attendance->clock_in)->format('H:i')) }}">
                ～ 
                <input type="text" name="clockout" value="{{ old('clockout', ($attendance->clock_out)->format('H:i')) }}">
            @else
                    <span>{{ ($attendance->clock_in)->format('H:i') }}
                    ～ 
                    {{ ($attendance->clock_out)->format('H:i') }}</span>
            @endif
            @error('clockin')
                <span class="error">{{ $message }}</span>
            @enderror
            @error('clockout')
                <span class="error">{{ $message }}</span>
            @enderror
        </td>
    </tr>


@foreach($attendance->breaks as $index => $break)
<tr>
    <th class="list2">休憩{{ $loop->iteration }}</th>
    <td class = "clockinout" colspan="4">
        @if ($editable)
            <input type="text" name="break_start[]" value="{{ old("break_start.$index", \Carbon\Carbon::parse($break->start_time)->format('H:i')) }}">
            ～
            <input type="text" name="break_end[]" value="{{ old("break_end.$index", \Carbon\Carbon::parse($break->end_time)->format('H:i'))}}">

            @if ($errors->has("break_start.$index"))
                <div class="error">{{ $errors->first("break_start.$index") }}</div>
            @endif
        @else
            <span>{{ \Carbon\Carbon::parse($break->start_time)->format('H:i') }} ～ {{ \Carbon\Carbon::parse($break->end_time)->format('H:i') }}</span>
        @endif
    </td>
</tr>
@endforeach

    <tr>
        <th class="list3">備考</th>
        @if ($editable)
            <td class="clockinout">
                <textarea name="note" cols="30" rows="3">{{ old('note', $attendance->note) }}</textarea>
                @error('note')
                    <div class="error">{{ $message }}</div>
                @enderror
            </td>
        @else
            <td>{{ $attendance->note }}</td>
        @endif
    </tr>
</table>
    @if ($editable)
        <button type="submit" class="btn--primary">修正</button>
    @else
    <p class="comment">承認待ちのため修正はできません。</p>
    @endif
</form>
@endsection