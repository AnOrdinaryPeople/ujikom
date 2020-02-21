@component('mail::message')
# Permintaan Reset Password

#### hai, {{ $data['name'] }}
Kamu mendapat pesan ini karena kami menerima permintaan reset password dari email ini.

@component('mail::button', ['url' => url('/forgot/reset?token='.$data['token'])])
Reset Password
@endcomponent

Permintaan ini akan berakhir dalam waktu 1 jam.

Jika kamu tidak mengirim permintaan ini, maka abaikan saja.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
