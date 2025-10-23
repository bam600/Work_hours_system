{{-- PG08:商品出品画面--}}

{{--layouts.appをベースレイアウトとして継承--}}
@extends('layouts.app')

@section('title', '商品出品')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')

<form action="{{ route('item.store') }}" method="post" novalidate> 
    @csrf
<div class="centered">
    <div class="product-detail">
        <div class="product-image">
            <h1 class="category-title">商品の出品</h1>
            <h4 class="category-title">商品画像</h4>
            <h2 class="category-title">商品の詳細</h2>
<h3 class="category-title">カテゴリー</h3>
<div class="category-buttons">
    @foreach($categories as $category)
        <label>
            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
            {{ $category->name }}
        </label>
    @endforeach

    {{-- エラー表示 --}}
    @error('categories')
        <div class="error">{{ $message }}</div>
    @enderror

    @foreach ($errors->get('categories.*') as $messages)
        @foreach ($messages as $message)
            <div class="error">{{ $message }}</div>
        @endforeach
    @endforeach
</div>
            <h3 class="category-title">商品の状態</h3>
            <select name="condition_id" id="condition" class="form-select"><option value="">選択してください</option>
                @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}"
                        {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                        {{ $condition->label }}
                    </option>
                @endforeach
            </select>
            @error('condition_id')
                <div class="error">{{ $message }}</div>
            @enderror
            

            <h2 class="category-title">商品と説明</h2>

            <h3 class="category-title">商品名</h3>
            <p><input type ="text" name="product_name" placeholder="商品名を入力してください" value="{{ old('product_name') }}" />
                    @error('product_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </p>

            <h3 class="category-title">ブランド名</h3>
                <p><input type ="text" name="brand" placeholder="ブランド名を入力してください" value="{{ old('brand') }}" />
                        @error('brand')
                            <div class="error">{{ $message }}</div>
                        @enderror
            </p>
            <h3 class="category-title">商品の説明</h3>
                <textarea name="description" class="description-box">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
            <h3 class="category-title">販売価格</h3>
                <p><input type="text" name="price" class="price-input" value="{{ old('price') }}">
                        @error('price')
                            <div class="error">{{ $message }}</div>
                        @enderror                
                </p>

            <p><button type="submit">出品する</button></p>
        </div>
    </div>
</div>
</form>
@endsection
