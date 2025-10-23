{{-- PG01:商品一覧画面（おすすめ／マイリストタブ切り替え付き）--}}

{{-- レイアウトの共通ファイルを継承（layouts/app.blade.phpを使う） --}}
@extends('layouts.app')

{{--タイトルタブの設定--}}
@section('title', '商品一覧')

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/product-list.css') }}">
@endsection

{{-- メインコンテンツ部分 --}}
@section('content') 

    {{-- タブ切り替えボタン部分--}}
    <div class="tab-buttons">

        {{-- 「おすすめ」ボタン --}}
        {{-- 押すとGETメソッドで /item?tab=recommend にアクセスする --}}
        <form method="GET" action="{{ route('item.index') }}">
            {{-- どのタブを開いたかをURLパラメータで送る --}}
            <input type="hidden" name="tab" value="recommend">

            {{-- 現在のタブが "recommend" ならボタンを強調表示 --}}
            <button type="submit" class="@if(request('tab') === 'recommend') active-button @else normal-button @endif">
                おすすめ
            </button>
        </form>

        {{-- 「マイリスト」ボタン --}}
        {{-- 押すとGETメソッドで /item?tab=mylist にアクセスする --}}
        <form method="GET" action="{{ route('item.index') }}">
            <input type="hidden" name="tab" value="mylist">

            {{-- 現在のタブが "mylist" ならボタンを強調表示 --}}
            <button type="submit" class="@if(request('tab') === 'mylist') active-button @else normal-button @endif">
                マイリスト
            </button>
        </form>
    </div>

    {{--商品一覧の表示エリア--}}
    <div class="product-grid">

        {{-- もし今のタブが「mylist」だったら --}}
        @if(request('tab') === 'mylist')

            {{--マイリストの表示（ログイン中ユーザーが「いいね」した商品） --}}
            @auth   {{-- ログイン中のときだけ表示される --}}
                @forelse ($likedProducts as $product)
                    {{-- 自分の商品はマイリストに出さない --}}
                    @if (Auth::id() !== $product->user_id)


                    <div class="product-card">
                        {{-- 商品詳細ページへのリンク --}}
                        <a href="{{ route('item.show', ['id' => $product->id]) }}">

                            {{-- 商品画像を表示 --}}
                            <img src="{{ asset($product->img) }}" alt="{{ $product->product_name }}" class="item_img">

                            {{-- 売り切れなら SOLD 表示 --}}
                            @if ($product->status === 'sold')
                                <p>SOLD</P>
                            @endif

                            {{-- 商品名を表示 --}}
                            <p class="product-name">{{ $product->product_name }}</p>
                        </a>
                    </div>
                @endif
            @empty
                {{-- いいねした商品がない場合の表示 --}}
                <p>まだいいねした商品はありません。</p>
            @endforelse
        @else
            {{-- 未ログイン時の表示 --}}
            <p>ログインするとマイリストが表示されます。</p>
        @endauth

    @else


    {{--おすすめ商品一覧表示（すべての出品を表示--}}
    @forelse ($exhibitions as $exhibition)
        <div class="product-card">
            {{-- 商品詳細ページへのリンク --}}
            <a href="{{ route('item.show', ['id' => $exhibition->id]) }}">
                {{-- 商品画像を表示 --}}
                <img src="{{ asset($exhibition->img) }}" alt="{{ $exhibition->product_name }}" class="item_img">

                {{-- 売り切れなら SOLD 表示 --}}
                @if ($exhibition->status === 'sold')
                    <p>SOLD</P>
                @endif

                {{-- 商品名を表示 --}}
                <p class="product-name">{{$exhibition->product_name }}
                </p>
            </a>
        </div>            
    @empty
        <p>現在、出品中の商品はありません。</p>
    @endforelse
@endif
</div>
@endsection