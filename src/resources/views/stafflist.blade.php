{{-- PG10 スタッフ一覧画面(管理者) --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', 'スタッフ一覧') 

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
    <h2 class="label">❙ スタッフ一覧</h2>
</div>

<table class="listtable">
    <tr>
        <th class="listleft1">名前</th>
        <th class="listleft2">メールアドレス</th>
        <th class="listleft2">月次勤怠</th>
    </tr>

    <!-- 検索結果↓ -->
    @foreach($stafflist as $list)
        <tr class="find">
            <th class="listleft4">{{ $list['user_name'] }}</th>
            <th class="listleft4">{{ $list['email'] }}</th>
        @if (!empty($list['id']))
            <th><a href="{{ route('staff.attendance', ['id' => $list['id']]) }}">詳細</a></th>
        @else
            <th>詳細</th>
        @endif
        </tr>
    @endforeach
</table>
@endsection