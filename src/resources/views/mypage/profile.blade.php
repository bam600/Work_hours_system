{{-- PG10 プロフィール設定画面 --}}

{{--継承--}}
@extends('layouts.app')

{{--タグタイトル--}}
@section('title', 'プロフィール登録(初回)')

{{--専用CSSを読み込む---}}
@section('head')
    <link rel="stylesheet" href="{{ asset('css/user-access.css') }}">
@endsection

@section('content')

<form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="table-wrapper">
    <table>
        <caption class="h2">プロフィール設定</caption>

            <tr>
                <td class="td">
                    <label>ユーザー名</label>
                </td>
                    
                <td>
                    <span class="error">
                        @error('user_name')
                            {{ $message }}
                        @enderror
                    </span>
                <td>
            </tr>

            <tr>
                <td>
                    <input type="text" name="user_name" placeholder="{{ $user->user_name ?? '' }}" value="{{ $user->user_name ?? '' }}" />
                </td>
            </tr> 
        
            <tr>
                <td class="td">
                    <label>郵便番号</label>
                </td>

                <td>
                    <span class="error">
                        @error('postal_code')
                            {{ $message }}
                        @enderror
                    </span>
                </td>
            </tr>

            <tr>   
                <td>
                    <input type="text" name="postal_code" placeholder="{{ $user->user_name ?? '' }}" value="{{ $user->profile->postal_code ?? '' }}" />
                </td>
            </tr>

            <tr>
                <td class="td">
                    <label>住所</label>
                </td>

                <td>
                    <span class="error">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </span>
                </td>
            </tr>

            <tr>
                <td>
                    <input type="text" name="address" placeholder="{{ $user->profile->address ?? '' }}"value="{{ $user->profile->address ?? '' }}" />
                </td>
            </tr>

            <tr>
                <td class="td">
                    <label>建物名</label>
                </td>

                <td>
                    <span class="error">
                        @error('building')
                            {{ $message }}
                        @enderror
                    </span>
                </td>
            </tr>

            <tr>
                <td>
                    <input type="text" name="building" placeholder="{{ optional($user->profile)->building }}" value="{{ optional($user->profile)->building }}"/>
                </td>
            </tr>

            <tr>
                <td colspan=2 class="center-link" >
                    <button type="submit" >更新する</button>
                </td>
            </tr>
    </table>
</form>
@endsection