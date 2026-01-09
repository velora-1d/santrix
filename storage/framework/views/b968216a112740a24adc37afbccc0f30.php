<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { text-align: center; margin-bottom: 20px; }
        .code-box { background: #f4f4f4; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; border-radius: 5px; margin: 20px 0; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Verifikasi Login Baru</h2>
        </div>
        
        <p>Halo,</p>
        <p>Kami mendeteksi login ke akun Santrix Anda dari perangkat atau lokasi baru.</p>
        <p>Gunakan kode verifikasi berikut untuk melanjutkan:</p>

        <div class="code-box">
            <?php echo e($token); ?>

        </div>

        <p>Kode ini akan kedaluwarsa dalam 15 menit.</p>
        <p>Jika Anda tidak merasa melakukan login ini, silakan abaikan email ini dan amankan akun Anda segera.</p>

        <div class="footer">
            &copy; <?php echo e(date('Y')); ?> Santrix by Velora. All rights reserved.
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\v\.gemini\antigravity\scratch\santrix\resources\views/emails/login-verification.blade.php ENDPATH**/ ?>