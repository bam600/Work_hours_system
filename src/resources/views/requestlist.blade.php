{{-- PG06,PG12 申請一覧画面 --}}
@extends('layouts.app')  

@section('title', '申請一覧画面') 

@section('head')    
    <link rel="stylesheet" href="{{ asset('css/attendancelist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login_auth.css') }}">
@endsection

@section('header')
    @if(Auth::user()->is_admin == 1)
        <div class="header__links">
            <a class="link" href="{{ route('adminrequest.list') }}">勤怠一覧</a>
            <a class="link" href="{{ route('stafflist') }}">スタッフ一覧</a>
            <a class="link" href="{{ route('request.list') }}">申請一覧</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
            <button type="submit" class="btm">ログアウト</button>
            </form>
        </div>
    @elseif(Auth::user()->is_admin == 0)
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
    <h2 class="label">❙ 申請一覧</h2>
</div>

@php
    $tab = request('tab', 'pending'); // デフォルトは 'pending'
@endphp

<div class="tab-buttons">
    <form method="GET" action="{{ route('request.list') }}">
        <input type="hidden" name="tab" value="pending">
        <a href="{{ route('request.list', ['tab' => 'pending']) }}" class="{{ $tab === 'pending' ? 'active-link' : 'normal-link' }}">
            承認待ち
        </a>
    </form>
    
    <form method="GET" action="{{ route('request.list') }}">
        <input type="hidden" name="tab" value="approved">
        <a href="{{ route('request.list', ['tab' => 'approved']) }}" class="{{ $tab === 'approved' ? 'active-link' : 'normal-link' }}">
            承認済み
        </a>
    </form>
</div>

<div class="tab-underline"></div>

<table class="listtable">
    <thead>
        <tr>
            <th class="listleft1">状態</th>
            <th class="listleft2">名前</th>
            <th class="listleft2">対象日時</th>
            <th class="listleft2">申請理由</th>
            <th class="listleft2">申請日時</th>
            <th class="listleft3">詳細</th>
        </tr>
    </thead>
<tbody>
@if(Auth::user()->is_admin == 1)
    {{-- 管理者向け：スタッフごとにグループ表示 --}}
    @if($tab === 'pending')
        @forelse($pendingRequests as $staffId => $requests)
            @foreach($requests as $request)
                <tr>
                    <td class="listleft4">承認待ち</td>
                    <td class="listleft4">{{ $request->staff->user_name }}</td>
                    <td class="listleft4">{{ $request->clock_in->format('Y/m/d') }}</td>
                    <td class="listleft4">{{ $request->note }}</td>
                    <td class="listleft4">{{ $request->created_at->format('Y/m/d') }}</td>
                    <td class="listleft4">
                        <a href="{{ route('adminattendance.info', ['id' => $request->id]) }}" class="infobtm">詳細</a>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr><td colspan="6">承認待ちの申請はありません。</td></tr>
        @endforelse
    @elseif($tab === 'approved')
        @forelse($approvedRequests as $staffId => $requests)
            <tr><td colspan="6" class="staff-header">{{ $requests->first()->staff->user_name }}</td></tr>
            @foreach($requests as $request)
                <tr>
                    <td class="listleft4">承認済み</td>
                    <td class="listleft4">{{ $request->staff->user_name }}</td>
                    <td class="listleft4">{{ $request->clock_in->format('Y/m/d') }}</td>
                    <td class="listleft4">{{ $request->note }}</td>
                    <td class="listleft4">{{ $request->created_at->format('Y/m/d') }}</td>
                    <td class="listleft4">
                        <a href="{{ route('attendance.info', ['id' => $request->id]) }}" class="infobtm">詳細</a>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr><td colspan="6">承認済みの申請はありません。</td></tr>
        @endforelse
    @endif
@else
    {{-- 一般ユーザー向け：そのままでOK --}}
    @if($tab === 'pending')
        @forelse($pendingRequests as $request)
            <tr>
                <td class="listleft4">承認待ち</td>
                <td class="listleft4">{{ $request->staff->user_name }}</td>
                <td class="listleft4">{{ $request->clock_in->format('Y/m/d') }}</td>
                <td class="listleft4">{{ $request->note }}</td>
                <td class="listleft4">{{ $request->created_at->format('Y/m/d') }}</td>
                <td class="listleft4">
                    <a href="{{ route('attendance.info', ['id' => $request->id]) }}" class="infobtm">詳細</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">承認待ちの申請はありません。</td></tr>
        @endforelse
    @elseif($tab === 'approved')
        @forelse($approvedRequests as $request)
            <tr>
                <td class="listleft4">承認済み</td>
                <td class="listleft4">{{ $request->staff->user_name }}</td>
                <td class="listleft4">{{ $request->clock_in->format('Y/m/d') }}</td>
                <td class="listleft4">{{ $request->note }}</td>
                <td class="listleft4">{{ $request->created_at->format('Y/m/d') }}</td>
                <td class="listleft4">
                    <a href="{{ route('attendance.info', ['id' => $request->id]) }}">詳細</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">承認済みの申請はありません。</td></tr>
        @endforelse
    @endif
@endif
</tbody>
</table>
@endsection
