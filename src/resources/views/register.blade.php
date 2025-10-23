{{-- PG03 会員登録登録画面 --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', '会員登録画面') 

{{--専用CSSを読み込む---}}
@section('head')    
    <link rel="stylesheet" href="{{ asset('css/user-access.css') }}">
@endsection

{{--以下会員登録フォーム--}}
@section('content')  

{{--登録を行うPOSTの送信先に、名前付きルート register.create を使用。--}}
<form action="{{ route('register.store') }}" method="post">    
    @csrf

    <div class="table-wrapper">
        <table>
            
            <caption class="h2">会員登録</caption>

            <tr>
                <td class="td">
                    <label>ユーザー名</label>
                </td>
                    
                <td>
                    {{--エラーメッセージ--}}
                    <span class="error">
                        @error('user_name') 
                            {{ $message }}
                        @enderror
                    </span>
                </td>
            </tr>
                
            <tr>
                <td colspan =2>
                    {{--old('user_name') で直前入力を保持。--}}
                    <input type ="text" name="user_name" placeholder="{{ old('email') }}"  value="{{ old('user_name') }}"/>
                </td>
            </tr>

            <tr>
                <td class="td">
                    <label>メールアドレス</label>
                </td>

                <td>
                    <span class="error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </span>
                </td>
            </tr>

            <tr>
                <td colspan =2><input type ="email" name="email" placeholder="{{ old('email') }}" value="{{ old('email') }}" />
                </td>
            </tr>

            <tr>
                <td class="td">
                    <label>パスワード</label>
                </td>

                <td>
                    <span class="error">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </span>
                </td>
            </tr>

            <tr>
                <td colspan =2><input type="password" name="password" />
                </td>
            </tr>

            <tr>
                <td class="td">
                    <label>確認用パスワード</label>
                </td>

                <td>
                    <span class="error">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </span> 
                </td>   
            </tr>
            
            <tr>
                <td>
                    <input type ="password" name="password_confirmation" value="{{ old('password_confirmation') }}" />
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <button type="submit">登録する</button>
                </td>
            </tr>

            <tr>
                <td colspan=2 class="center-link" >
                    <a href="{{ route('login') }}">ログインはこちら</a>
                </td>
            </tr>
        </table>
    </div>
</form>
@endsection