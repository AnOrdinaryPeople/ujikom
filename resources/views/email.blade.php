@component('mail::message')
# Verifikasi Email

#### Hai, {{ $data['name'] }}
Klik Tombol dibawah ini untuk memverifikasi email kamu

@component('mail::button', ['url' => url('/verify?token='.$data['token'])])
Klik Disini!
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
