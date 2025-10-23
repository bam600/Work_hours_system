@extends('layouts.app') {{--継承--}}

@section('title', 'ログイン画面')   {{--タグタイトル--}}
@section('head')    {{--専用CSSを読み込む---}}
    <link rel="stylesheet" href="{{ asset('css/user-access.css') }}">
@endsection
@section('content')

{{-- /loginがフォーム送信先でPOSTメソッドで/loginに送信 --}}
<form action="{{ route('login.store') }}" method="POST">
    @csrf
    <div class="table-wrapper">
    <table>
        <th colspan=4><h2 class="h2">ログイン</h2></th>
    <tr>
        
        <td class="td"><label>メールアドレス</label></td>
            <td>
                <span class="error"> 
                    @error('email')
                        {{ $message }}
                    @enderror
                </span>
            </td>
            <td>
                <span class="error">
                    @error('auth')
                        {{ $message }}
                    @enderror
                </span>
            </td>
    </tr>

    <tr>    
        <td><input type ="text" name="email" placeholder="1234@gmail.com" value="{{ old('email') }}" /></td>
    </tr>

    <tr>
        <td class="td"><label>パスワード</label></td>
            <td>
            <span class="error"> 
                @error('password')
                    <span class="error"> {{ $message }}</span>
                @enderror
            </span>
        </td>
        
    </tr>
    

    <tr>
        <td colspan=4><input type ="password" name="password" placeholder="12345abcde" /></td>
    </tr>





    <tr>
        <!-- button：フォーム送信ボタン ログインするボタンを押下するとform実装-->
        <td colspan=2 ><button type="submit">ログインする</button></td>
    </tr>

    <tr>
        <!-- route('register')：名前付きルートを使って会員登録画面へリンク-->
        <td colspan=2 class="center-link" ><a href="{{ route('register') }}">会員登録はこちら</a></td>
    </tr>
</table>
    </form>
@endsection