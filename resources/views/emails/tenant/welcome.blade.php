<x-mail::message>
# Selamat Datang di Santrix! 🎉

Halo Admin **{{ $pesantrenName }}**,

Pendaftaran akun pesantren Anda telah berhasil. Sekarang Anda dapat mengelola manajemen santri, keuangan, dan akademik dalam satu aplikasi terintegrasi.

## Detail Login Pesantren
Login melalui dashboard khusus Anda di bawah ini:

<x-mail::panel>
**URL:** [{{ $loginUrl }}]({{ $loginUrl }})  
**Email:** {{ $email }}  
**Password:** {{ $password }}  
</x-mail::panel>

<x-mail::button :url="$loginUrl">
Login ke Dashboard
</x-mail::button>

## Informasi Akun
* **Masa Trial:** Aktif hingga {{ $trialEnd }}
* **Status:** Trial Mode

_Harap segera ganti password Anda setelah login pertama kali untuk keamanan._

Jika Anda membutuhkan bantuan, silakan balas email ini atau hubungi support kami via WhatsApp.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
