<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - PILIHANKU</title>
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
            padding: 40px;
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
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
        }
        
        .intro-text {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        
        .security-notice {
            background: #fff7ed;
            border-left: 4px solid #f59e0b;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .security-notice-title {
            font-weight: 700;
            color: #92400e;
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .security-notice-text {
            font-size: 13px;
            color: #78350f;
            line-height: 1.6;
        }
        
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        
        .reset-button {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
        }
        
        .expiry-info {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .expiry-info-text {
            font-size: 14px;
            color: #166534;
            font-weight: 600;
        }
        
        .alternative-link {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 16px;
            border-radius: 8px;
            margin-top: 25px;
        }
        
        .alternative-link-title {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .alternative-link-url {
            word-break: break-all;
            font-size: 12px;
            color: #667eea;
            font-family: 'Courier New', monospace;
            background: #ffffff;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 30px 0;
        }
        
        .not-requested {
            font-size: 13px;
            color: #6b7280;
            margin-top: 25px;
            padding: 16px;
            background: #fef2f2;
            border-radius: 8px;
            border-left: 4px solid #ef4444;
        }
        
        .not-requested strong {
            color: #991b1b;
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
        
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 25px 20px;
            }
            
            .email-header {
                padding: 30px 20px;
            }
            
            .email-footer {
                padding: 25px 20px;
            }
            
            .logo {
                font-size: 26px;
            }
            
            .reset-button {
                padding: 14px 30px;
                font-size: 15px;
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
            <div class="greeting">Hello!</div>
            
            <p class="intro-text">
                You are receiving this email because we received a password reset request for your account.
            </p>
            
            <div class="button-container">
                <a href="<?php echo e($url); ?>" class="reset-button">Reset Password</a>
            </div>
            
            <div class="expiry-info">
                <div class="expiry-info-text">
                    ⏱️ This password reset link will expire in <?php echo e($expiresInMinutes ?? 60); ?> minutes.
                </div>
            </div>
            
            <div class="security-notice">
                <div class="security-notice-title">
                    <span>⚠️</span>
                    <span>Security Notice</span>
                </div>
                <div class="security-notice-text">
                    For your security, please do not share this link with anyone. If you did not request a password reset, please ignore this email or contact our support team.
                </div>
            </div>
            
            <div class="alternative-link">
                <div class="alternative-link-title">
                    If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                </div>
                <div class="alternative-link-url"><?php echo e($url); ?></div>
            </div>
            
            <div class="divider"></div>
            
            <div class="not-requested">
                <strong>If you did not request a password reset, no further action is required.</strong>
            </div>
            
            <p style="margin-top: 25px; font-size: 14px; color: #4b5563;">
                Regards,<br>
                <strong>Pilihanku</strong>
            </p>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-text">
                © <?php echo e(date('Y')); ?> PILIHANKU. All rights reserved.
            </div>
            
            <div class="footer-links">
                <a href="<?php echo e(config('app.url')); ?>" class="footer-link">Dashboard</a>
                <a href="<?php echo e(config('app.url')); ?>/prodi" class="footer-link">Program Studi</a>
                <a href="<?php echo e(config('app.url')); ?>/siswa/spk/form" class="footer-link">SPK Analysis</a>
            </div>
            
            <div style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                Jl. Contoh No. 123, Jakarta, Indonesia<br>
                Email: support@pilihanku.com
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\tugas_akhir\resources\views/emails/reset-password.blade.php ENDPATH**/ ?>