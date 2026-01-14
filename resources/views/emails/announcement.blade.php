<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailTitle }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 40px;
            text-align: center;
            color: #ffffff;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .tagline {
            font-size: 14px;
            opacity: 0.95;
            font-weight: 500;
        }
        
        .email-body {
            padding: 40px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        
        .notification-card {
            background: #f9fafb;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .notification-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }
        
        .notification-message {
            font-size: 15px;
            color: #4b5563;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        
        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 30px 0;
        }
        
        .email-footer {
            background-color: #f9fafb;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-text {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .footer-links {
            margin-top: 15px;
        }
        
        .footer-link {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            margin: 0 10px;
            font-weight: 500;
        }
        
        .footer-link:hover {
            text-decoration: underline;
        }
        
        .social-links {
            margin-top: 20px;
        }
        
        .social-link {
            display: inline-block;
            width: 36px;
            height: 36px;
            background-color: #667eea;
            color: #ffffff;
            border-radius: 50%;
            text-align: center;
            line-height: 36px;
            margin: 0 5px;
            text-decoration: none;
            font-weight: 600;
        }
        
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 25px 20px;
            }
            
            .email-header {
                padding: 25px 20px;
            }
            
            .email-footer {
                padding: 25px 20px;
            }
            
            .logo {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">PILIHANKU</div>
            <div class="tagline">Sistem Pendukung Keputusan Pemilihan Program Studi</div>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            @if($userName)
            <div class="greeting">
                Halo, {{ $userName }}! 👋
            </div>
            @endif
            
            <div class="notification-card">
                @if($type === 'success')
                <span class="badge badge-success">✓ Success</span>
                @elseif($type === 'warning')
                <span class="badge badge-warning">⚠ Important</span>
                @else
                <span class="badge badge-info">📢 Announcement</span>
                @endif
                
                <div class="notification-title">{{ $mailTitle }}</div>
                <div class="notification-message">{{ $mailMessage }}</div>
            </div>
            
            <a href="{{ config('app.url') }}" class="btn">Buka Dashboard</a>
            
            <div class="divider"></div>
            
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 5px;">
                Email ini dikirim secara otomatis oleh sistem PILIHANKU. Jika Anda tidak ingin menerima email notifikasi, silakan ubah pengaturan notifikasi di profil Anda.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-text">
                © {{ date('Y') }} PILIHANKU. All rights reserved.
            </div>
            
            <div class="footer-links">
                <a href="{{ config('app.url') }}" class="footer-link">Dashboard</a>
                <a href="{{ config('app.url') }}/prodi" class="footer-link">Program Studi</a>
                <a href="{{ config('app.url') }}/siswa/spk/form" class="footer-link">SPK Analysis</a>
            </div>
            
            <div style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                Jl. Contoh No. 123, Jakarta, Indonesia<br>
                Email: support@pilihanku.com
            </div>
        </div>
    </div>
</body>
</html>
