{{-- PG09:マイページ画面--}}

{{--layouts.appをベースレイアウトとして継承--}}
@extends('layouts.app') 

{{--タイトルタグの設定--}}
@section('title', 'マイページ')
@section('head')    {{--専用CSSを読み込む---}}
    <link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
@endsection
@section('content') 
    {{-- profile編集画面リンクボタン --}}
    <p>{{ Auth::user()->user_name }}</p>
    <div class="tab-buttons">
        <div class="profile_link">
            {{-- 実施のリンク箇所 (Profile.edit=プロフィール編集画面)--}}
            <a class="profile_text" href="{{ route('profile.edit') }}">プロフィール選択</a>
        </div>
        
        {{--mapageルートにGETリクエストを送るフォーム--}}
        <form method="GET" action="{{ route('mypage') }}"> 
            {{--URLに?page=sellを含める(タブ切り替え用)--}}
            <input type="hidden" name="page" value="sell">
            {{--ボタンを押すとフォーム送信 現在のページのvalueがsellの場合
                『出品した商品』ボタンを赤くする--}}
            <button type="submit" class="@if(request('page') === 'sell') active-button @else normal-button @endif">出品した商品
            </button>
        </form>

        {{--mapageルートにGETリクエストを送るフォーム--}}
        <form method="GET" action="{{ route('mypage') }}"> 
            {{--URLに?page=buyを含める(タブ切り替え用)--}}
            <input type="hidden" name="page" value="buy">
            {{--ボタンを押すとフォーム送信 現在のページのvalueがbuyの場合
                『出品した商品』ボタンを赤くする--}}
            <button type="submit" class="@if(request('page') === 'buy') active-button @else normal-button @endif">購入した商品
            </button>
        </form>

    </div>

    {{--出品中の商品一覧を表示するため処理--}}
    {{--URLクエリパラメータ ?page=sell が指定されているかを判定--}}
    @if(request('page') === 'sell')
        {{--出品中の商品が1件以上あるかを判定 商品がある場合は一覧表示---}}     
        @if($exhibitions->isNotEmpty())
            {{--出品商品を1件ずつ表示--}}
            @foreach ($exhibitions as $exhibition)
                <div class="exhibition-card">
                {{--商品名を表示--}}    
                    <a href="{{ route('item.show', ['id' => $exhibition->id]) }}">
                <h4> <img src="{{ asset($exhibition->img) }}" alt="{{ $exhibition->product_name }}" class="item_img"></h4>
                    <p>{{ $exhibition->product_name }}</p>
                </div>
            @endforeach

        {{--商品が1件もない場合の表示--}}    
        @else
            <p>現在、出品中の商品はありません。</p>
        @endif
    @endif

@endsection