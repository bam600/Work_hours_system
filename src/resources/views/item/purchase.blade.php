{{-- PG06:商品購入画面:purchase.blade.php--}}


{{--layouts.appをベースレイアウトとして継承--}}
@extends('layouts.app') 

{{--タイトルタグの設定--}}
@section('title', '商品購入')

{{--商品詳細用のCSSを読み込む---}}
@section('head')
    <link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
@endsection

@section('content')
    <div class="centered">
        <div class="product-detail">
            <div class="product-image">
                {{-- セッションから取得した画像を表示 --}}
                <p><img class="product_img" src="{{ $img }}" alt="商品画像" /></p>
            </div>
            <div class="product-text">
                {{-- セッションから取得した価格を表示 --}}
                <p><span class="contents4">{{ $exhibition->product_name }}</span></p>
                {{-- セッションから取得した価格を表示 --}}
                <p><span class="contents4">￥{{ number_format($price) }} (税込み)</span></p>
                @php
                    $product_price = number_format($price);
                @endphp
                {{-- メッセージ表示 --}}
                <p>{{ $message }}</p>

                <h3 class="category-title">支払い方法</h3>
                    @livewire('payment-selector', ['exhibitionId' => $exhibition->id])

                <div>
                    <p>商品代金</p>
                    <p>¥{{$product_price}}</p>
                </div>

                <p>配送先</p>
                    <a href="{{ route('address', ['id' => $exhibition->id]) }}">変更する</a>
                        <p>{{$postal_code}}</p>
                        <p>{{$address}}</p>
                        <p>{{$building}}</p>
            </div>



        {{-- エラーを見える化 --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('order', ['id' => $exhibition->id]) }}">
            @csrf

        {{-- セッション直参照はやめ、コントローラから渡された画面変数を使う --}}
        <input type="hidden" name="payment_id"    value="{{ $selected_payment_id }}">
        <input type="hidden" name="postal_code"   value="{{ $postal_code }}">
        <input type="hidden" name="address_line" value="{{ $address }}">
        <input type="hidden" name="building_name" value="{{ $building }}">
        <input type="hidden" name="exhibition_id" value="{{ $exhibition->id }}">
        <input type="hidden" name="price" value="{{ $exhibition->price }}">

            <p><button type="submit">購入する</button></p>
        </form>
    </div>
</div>
@endsection