{{-- PG03 勤怠登録画面 --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '勤怠画面') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('attendance.css') }}">
@endsection

{{--以下会員登録フォーム--}}
@section('content')  

{{--登録を行うPOSTの送信先に、名前付きルート register.store を使用。--}}
<form action="{{ route('register.store') }}" method="POST">
    @csrf

<div class="register-wrapper">
    <h2 class="form-title">勤怠外</h2>


    <button type="submit" class="btn btn--primary">出勤</button>
</div>
</form>

            <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">ログアウト</button>
            </form>
@endsection