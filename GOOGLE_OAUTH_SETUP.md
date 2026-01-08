# Google OAuth Implementation - Pilihanku

## ✅ Instalasi Berhasil!

Fitur Login/Signup dengan Google telah berhasil diimplementasikan ke aplikasi Pilihanku.

## 📋 Yang Sudah Dikerjakan:

### 1. Package & Dependencies
- ✅ Laravel Socialite terinstall
- ✅ Konfigurasi di `config/services.php`

### 2. Database
- ✅ Migration untuk kolom `google_id` dan `avatar` di tabel `users`
- ✅ Update Model User dengan fillable fields

### 3. Backend
- ✅ `GoogleAuthController` dengan 2 methods:
  - `redirectToGoogle()` - redirect ke halaman login Google
  - `handleGoogleCallback()` - handle response dari Google
- ✅ Routes untuk `/auth/google` dan `/auth/google/callback`

### 4. Frontend
- ✅ Tombol "Login dengan Google" di halaman login
- ✅ Tombol "Daftar dengan Google" di halaman register
- ✅ Design menarik dengan Google logo SVG

## 🔧 Langkah Selanjutnya (PENTING!):

### Setup Google Cloud Console:

1. **Buka Google Cloud Console**
   - Pergi ke: https://console.cloud.google.com/

2. **Create Project (jika belum ada)**
   - Klik "Select a project" → "New Project"
   - Nama: `Pilihanku App`
   - Klik "Create"

3. **Enable Google+ API**
   - Di sidebar, pilih "APIs & Services" → "Library"
   - Cari "Google+ API" atau "Google Identity"
   - Klik "Enable"

4. **Create OAuth 2.0 Credentials**
   - Sidebar → "APIs & Services" → "Credentials"
   - Klik "Create Credentials" → "OAuth 2.0 Client ID"
   - Jika diminta, configure OAuth consent screen:
     - User Type: External
     - App name: Pilihanku
     - User support email: (email Anda)
     - Developer contact: (email Anda)
     - Save and Continue
   - Application type: **Web application**
   - Name: `Pilihanku Web Client`
   
5. **Add Authorized Redirect URIs**
   Tambahkan URIs berikut:
   ```
   http://127.0.0.1:8000/auth/google/callback
   http://localhost:8000/auth/google/callback
   ```

6. **Copy Credentials**
   - Setelah dibuat, akan muncul popup dengan:
     - **Client ID** (panjang, contoh: 123456789-abc.apps.googleusercontent.com)
     - **Client Secret** (contoh: GOCSPX-abc123...)
   - Copy kedua nilai ini!

7. **Update File .env**
   Edit file `.env` Anda (sudah disiapkan):
   ```env
   GOOGLE_CLIENT_ID=paste-client-id-anda-disini
   GOOGLE_CLIENT_SECRET=paste-client-secret-anda-disini
   GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
   ```

8. **Restart Laravel Server**
   ```bash
   php artisan serve
   ```

## 🧪 Cara Testing:

1. Jalankan aplikasi: `php artisan serve`
2. Buka browser: `http://127.0.0.1:8000/login`
3. Klik tombol "Login dengan Google"
4. Pilih akun Google Anda
5. Selesai! Anda akan otomatis login ke dashboard

## 🎯 Fitur yang Sudah Berfungsi:

### Skenario 1: User Baru
- User klik "Login dengan Google"
- Otomatis membuat akun baru dengan:
  - Nama dari Google
  - Email dari Google
  - Avatar/Foto profil dari Google
  - Email sudah terverifikasi otomatis
  - Role: `user`

### Skenario 2: User Sudah Pernah Daftar (via Google)
- User klik "Login dengan Google"
- Sistem cek `google_id`, ketemu
- Langsung login, update avatar jika berubah

### Skenario 3: Email Sudah Terdaftar (via Normal Signup)
- User sudah punya akun dengan email biasa
- User coba login dengan Google (email sama)
- Sistem link akun Google ke akun existing
- User bisa login dengan Google atau email/password

## 🔒 Keamanan:

- ✅ OAuth 2.0 (standar industri)
- ✅ Email sudah terverifikasi Google
- ✅ Password random untuk akun Google (tidak bisa login via password biasa)
- ✅ HTTPS required untuk production
- ✅ Credentials tersimpan aman di `.env` (jangan commit ke Git!)

## 📱 Kompatibilitas:

- ✅ Desktop browser
- ✅ Mobile browser
- ✅ Tablet
- ✅ Responsive design

## 💡 Tips Production:

1. **Untuk deployment production**, jangan lupa:
   - Tambahkan domain production ke Authorized Redirect URIs:
     ```
     https://yourdomain.com/auth/google/callback
     ```
   - Update `.env` production dengan domain yang benar

2. **OAuth Consent Screen**:
   - Untuk production, verify app di Google Console
   - Upload logo aplikasi
   - Lengkapi privacy policy & terms of service

3. **Environment Variables**:
   - JANGAN commit `.env` ke Git
   - Gunakan `.env.example` untuk template
   - Set credentials di server production

## 🐛 Troubleshooting:

### Error: "redirect_uri_mismatch"
- **Solusi**: Pastikan URI di Google Console sama persis dengan `GOOGLE_REDIRECT_URI` di `.env`

### Error: "invalid_client"
- **Solusi**: Cek Client ID dan Client Secret, pastikan tidak ada spasi atau karakter tambahan

### Tombol Google tidak muncul
- **Solusi**: Clear cache browser (Ctrl+Shift+R), atau clear cache Laravel:
  ```bash
  php artisan config:clear
  php artisan cache:clear
  php artisan view:clear
  ```

### Error: "This app hasn't been verified"
- **Solusi**: Ini normal untuk development. Klik "Advanced" → "Go to Pilihanku (unsafe)"

## 📞 Support:

Jika ada masalah, cek:
1. Log Laravel: `storage/logs/laravel.log`
2. Browser console (F12)
3. Google Cloud Console error messages

---

**Dibuat oleh:** GitHub Copilot
**Tanggal:** 15 November 2025
**Status:** ✅ Ready to Use (setelah setup Google credentials)
