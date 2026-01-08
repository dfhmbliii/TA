<?php

// Simple test script to verify email config
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "Testing email configuration...\n";
echo "MAIL_HOST: " . config('mail.host') . "\n";
echo "MAIL_PORT: " . config('mail.port') . "\n";
echo "MAIL_USERNAME: " . config('mail.username') . "\n";
echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n";
echo "\nAttempting to send test email...\n";

try {
    Mail::raw('Test email dari Pilihanku untuk verifikasi konfigurasi.', function($m) {
        $m->to('wawan.dafa02@gmail.com')->subject('Test Email - Pilihanku');
    });
    echo "✓ Email berhasil dikirim! Periksa inbox Anda.\n";
} catch (\Throwable $e) {
    echo "✗ Gagal mengirim email:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "\nCek hal berikut:\n";
    echo "1. MAIL_FROM_ADDRESS harus diisi (sekarang: " . config('mail.from.address') . ")\n";
    echo "2. Gmail App Password harus benar (tanpa spasi yang tidak perlu)\n";
    echo "3. 2FA harus diaktifkan di Gmail\n";
    echo "4. Port 587 terbuka (TLS)\n";
}
?>
