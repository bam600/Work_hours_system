{{-- PG06 申請一覧(一般) --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '申請一覧画面') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/attendancelist.css') }}">
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
    <h2 class="label">❙ 申請一覧</h2>
</div>


<table class="listtable">
    <tr>
        <th class="listleft1">状態</th>
        <th class="listleft2">名前</th>
        <th class="listleft2">対象日時</th>
        <th class="listleft2">申請理由</th>
        <th class="listleft2">申請日時</th>
        <th class="listleft3">詳細</th>
    </tr>

    <!-- 検索結果↓ -->
    <tr class="find">
        <th class="listleft4"></th>
        <th class="listleft4"></th>
        <th class="listleft4"></th>
        <th class="listleft4"></th>
        <th class="listleft4"></th>
        <th class="listleft4">詳細</th>
    </tr>
</table>
@endsection