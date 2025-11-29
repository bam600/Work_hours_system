{{-- mail認証画面 --}}

{{--共通レイアウトの継承--}}
@extends('layouts.app')  

{{--タグタイトル--}}
@section('title', 'メール確認')

<style>
.verify-wrapper {
    display: flex;
    flex-direction: column;
    /* 縦並び */
    justify-content: center;
    /* 縦方向中央 */
    align-items: center;
    /* 横方向中央 */
    min-height: 100vh;
    /* 画面全体の高さを確保 */
    text-align: center;
    /* テキストも中央寄せ */
}
/* タイトル */
.form-title {
    font-size: 33px;
    /* ← 22px × 1.5 */
    font-weight: 700;
    margin-bottom: 42px;
    /* ← 28px × 1.5 */
    text-align: center;
}

.form-group {
    display: flex;
    justify-content: center;
    position: relative;
    /* 子要素の絶対位置の基準に */
    margin-bottom: 36px;
    width: 100%;
}

/* フォーカス時の枠線 */
.form-group input:focus {
    border-color: #ff4c4c;
    outline: none;
}

/* エラーメッセージなどを下に置く場合 */
.error {
    display: block;
    color: red;
    font-size: 19px;
    margin-top: 6px;
}

p{
    font-family: 'Inter', sans-serif;
    font-size: 24px;
    font-weight: bold;
    line-height: 0.5;
}

/* ボタン */
.btn--primary {
    width: 100%;
    color: #000;
    border: 2px solid black;
    padding: 18px;
    /* ← 12px × 1.5 */
    border-radius: 6px;
    /* ← 6px × 1.5 */
    cursor: pointer;
    font-size: 24px;
    /* ← 16px × 1.5 */
    font-weight: 600;
    letter-spacing: 0.03em;
    transition: background 0.2s ease;
    font-family: 'Inter', sans-serif;
    margin-top: 20px;
}

.center-link {
    background: none;
    /* 背景を消す */
    border: none;
    /* 枠線を消す */
    color: #007bff;
    /* リンクっぽい青色 */
    padding: 0;
    font: inherit;
    cursor: pointer;
    text-decoration: none;
    font-family: 'Inter', sans-serif;
    margin-top: 20px;
}

.center-link:hover {
    text-decoration: underline;
    /* ホバー時だけ下線を出す */
}

</style>

{{--以下会員登録フォーム--}}
@section('content')
<div class="verify-wrapper">
    <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p>メール認証を完了してください。</p>

    <form method="GET" action="{{ route('attendance.create') }}">
    <button type="submit" class="btn--primary">認証はこちらから</button>
    </form>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="center-link">認証メールを再送する</button>
    </form>
</div>
@endsection