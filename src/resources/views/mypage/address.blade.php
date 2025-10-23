{{-- PG07 送付先住所変更画面 --}}
@extends('layouts.app')  {{--継承--}}
@section('title', '送付先住所変更')  {{--タグタイトル--}}
@section('head')    {{--専用CSSを読み込む---}}
    <link rel="stylesheet" href="{{ asset('css/user-access.css') }}">
@endsection

@section('content')

<form method="POST" action="{{ route('address.change', ['id' => $exhibition_id]) }}">
    @csrf
    <!-- 以下、テーブル構造はそのままでOK --> 
    <div class="table-wrapper">
    <table>
        <tr>
            <th><h2 class="h2">住所変更</h2></th>
        </tr>
        
            <tr><td class="td"><label>郵便番号</label></td>
                <td>
                    <span class="error">
                            @error('postal_code')
                                {{ $message }}
                            @enderror
                    </span>
                </td>
            </tr>

            <tr>   
                <td><input type="text" name="postal_code" value="{{ old('postal_code', $postal_code) }}"></td>
            </tr>

            <tr>
                <td class="td"><label>住所</label></td>
                <td>
                    <span class="error">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </span>
                </td>
            </tr>
            <tr>
                <td><input type="text" name="address" value="{{ old('address', $address) }}"></td>
            </tr>

            <tr>
                <td class="td"><label>建物名</label></td>
                    <td>
                        <span class="error">
                            @error('building')
                                {{ $message }}
                            @enderror
                        </span>
                    </td>
            </tr>

            <tr>
                <td><input type="text" name="building" value="{{ old('building', $building) }}"></td>

            <tr>
                <td colspan=2 class="center-link" ><button type="submit" >更新する</button></td>
            </tr>
</table>
</form>
@endsection