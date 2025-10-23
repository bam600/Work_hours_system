@extends('layouts.app') 

@section('title', '商品詳細')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/details.css') }}">
@endsection

@section('content')
<div class="centered">
    <div class="product-detail">
        <div class="product-image">
            <p><img class="product_img" src="{{ $exhibition->img }}" alt="{{ $exhibition->product_name }}"/></p>
        </div>

        <div class="product-text">
            <p><span class="contents1">{{ $exhibition->product_name }}</span></p>
            <p><span class="brand_name">{{ $exhibition->brand }}</span></p>
            <p><span class="contents4">￥{{ number_format($exhibition->price) }} (税込み)</span></p>

            {{-- いいねボタン --}}
            @auth
                @if($exhibition->isLikedBy(Auth::user()))
                    <form method="POST" action="{{ route('like.destroy', $exhibition->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">★ いいね</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('like.store', $exhibition->id) }}">
                        @csrf
                        <button type="submit">☆ いいね</button>
                    </form>
                @endif
                <p>いいね数：{{ $exhibition->likes_count ?? 0 }}</p>
            @endauth
            @if($exhibition->status!=="sold")
            {{-- 購入手続きボタン（独立フォーム） --}}
            <form method="POST" action="{{ route('item.prepare') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $exhibition->id }}">
                <button type="submit">購入手続きへ</button>
            </form>
            @else
                <p>売り切れの商品です</p>
            @endif

            {{-- 商品説明・情報 --}}
            <p><label class="contents2">商品説明</label></p>
            <p><span class="contents5">{{ $exhibition->description }}</span></p>
            <p><label class="contents2">商品情報</label></p>
            <p><label class="contents5">カテゴリー</label></p>
            @foreach($categories as $category)
                <p>{{ $category->name }}</p>
            @endforeach
            <p><label class="contents5">商品の状態</label></p>
            <p><span>{{ $exhibition->condition->label }}</span></p>
        </div>
    </div>
</div>
@endsection
