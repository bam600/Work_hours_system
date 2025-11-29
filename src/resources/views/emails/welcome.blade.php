@component('mail::message')
# メール認証のお願い

以下のボタンをクリックして、メール認証を完了してください。

@component('mail::button', ['url' => route('verification.notice')])
メール認証する
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
