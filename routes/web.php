<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\GoogleAuthController;

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Forgot password & reset
Route::get('/password/forgot', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        if (in_array(Auth::user()->role, ['siswa', 'mahasiswa'])) {
            return redirect()->route('siswa.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // siswa Dashboard
    Route::get('/siswa/dashboard', [App\Http\Controllers\SiswaDashboardController::class, 'index'])->name('siswa.dashboard');

    // siswa Profile & Settings (accessible by siswa role)
    Route::middleware(['check.role:siswa'])->group(function () {
        Route::get('/siswa/profile', [App\Http\Controllers\SiswaProfileController::class, 'showProfile'])->name('siswa.profile');
        Route::put('/siswa/profile', [App\Http\Controllers\SiswaProfileController::class, 'updateProfile'])->name('siswa.profile.update');
        Route::get('/siswa/settings', [App\Http\Controllers\SiswaProfileController::class, 'showSettings'])->name('siswa.settings');
        Route::put('/siswa/password', [App\Http\Controllers\SiswaProfileController::class, 'updatePassword'])->name('siswa.password.update');
        Route::put('/siswa/notifications', [App\Http\Controllers\SiswaProfileController::class, 'updateNotifications'])->name('siswa.notifications.update');
        Route::delete('/siswa/account', [App\Http\Controllers\SiswaProfileController::class, 'deleteAccount'])->name('siswa.account.delete');

        // Siswa SPK Form & Calculate
        Route::get('/siswa/spk/form', [App\Http\Controllers\SiswaSpkController::class, 'showForm'])->name('siswa-spk.form');
        Route::post('/siswa/spk/calculate', [App\Http\Controllers\SiswaSpkController::class, 'calculate'])->name('siswa-spk.calculate');
    });

    // Admin Only Routes
    Route::middleware(['check.role:admin'])->group(function () {
        // Admin Profile & Settings
        Route::get('/admin/profile', [App\Http\Controllers\AdminProfileController::class, 'showProfile'])->name('admin.profile');
        Route::get('/admin/announcement', [App\Http\Controllers\SystemAnnouncementController::class, 'form'])->name('admin.announcement.form');
        Route::post('/admin/announcement', [App\Http\Controllers\SystemAnnouncementController::class, 'store'])->name('admin.announcement.store');
        Route::put('/admin/profile', [App\Http\Controllers\AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');
        Route::get('/admin/settings', [App\Http\Controllers\AdminProfileController::class, 'showSettings'])->name('admin.settings');
        Route::put('/admin/password', [App\Http\Controllers\AdminProfileController::class, 'updatePassword'])->name('admin.password.update');
        Route::put('/admin/notifications', [App\Http\Controllers\AdminProfileController::class, 'updateNotifications'])->name('admin.notifications.update');

        // Account Deletion Requests Management
        Route::get('/admin/account-deletion-requests', [App\Http\Controllers\AccountDeletionController::class, 'index'])->name('admin.deletion-requests');
        Route::post('/admin/account-deletion-requests/{id}/approve', [App\Http\Controllers\AccountDeletionController::class, 'approve'])->name('admin.deletion-requests.approve');
        Route::post('/admin/account-deletion-requests/{id}/reject', [App\Http\Controllers\AccountDeletionController::class, 'reject'])->name('admin.deletion-requests.reject');

        // User Management
        Route::resource('users', UserController::class);

        // Prodi Management (Create, Update, Delete - Admin Only)
        Route::post('/prodi', [ProdiController::class, 'store'])->name('prodi.store');
        Route::get('/prodi/create', [ProdiController::class, 'create'])->name('prodi.create');
        Route::put('/prodi/{prodi}', [ProdiController::class, 'update'])->name('prodi.update');
        Route::delete('/prodi/{prodi}', [ProdiController::class, 'destroy'])->name('prodi.destroy');
        Route::get('/prodi/{prodi}/edit', [ProdiController::class, 'edit'])->name('prodi.edit');
        Route::get('/prodi/{prodi}/kurikulum', [ProdiController::class, 'editKurikulum'])->name('prodi.kurikulum');
        Route::post('/prodi/{prodi}/kurikulum', [ProdiController::class, 'updateKurikulum'])->name('prodi.kurikulum.update');

        // siswa Management
        Route::resource('siswa', SiswaController::class);
        Route::post('/siswa/{id}/reset-password', [SiswaController::class, 'resetPassword'])->name('siswa.reset-password');

        // SPK calculate for single siswa
        Route::post('/siswa/{id}/spk', [App\Http\Controllers\SpkResultController::class, 'calculate'])->name('siswa.spk.calculate');

        // SPK Analysis Dashboard (Admin Only)
        Route::get('/spk/analysis-dashboard', [App\Http\Controllers\SpkDashboardController::class, 'index'])->name('spk.dashboard');

        // SPK Analysis - AHP Criteria Management (Admin Only)
        Route::get('/spk/analysis', [App\Http\Controllers\SpkAnalysisController::class, 'index'])->name('spk.analysis');
        Route::post('/spk/analysis/pairwise', [App\Http\Controllers\SpkAnalysisController::class, 'storePairwise'])->name('spk.store-pairwise');
        Route::post('/spk/analysis/apply-weights', [App\Http\Controllers\SpkAnalysisController::class, 'applyCurrentWeights'])->name('spk.apply-weights');
        Route::post('/spk/analysis/initialize', [App\Http\Controllers\SpkAnalysisController::class, 'initializeCriteria'])->name('spk.initialize-criteria');
        Route::put('/spk/analysis/kriteria/{id}', [App\Http\Controllers\SpkAnalysisController::class, 'updateKriteria'])->name('spk.update-kriteria');
        Route::delete('/spk/analysis/kriteria/{id}', [App\Http\Controllers\SpkAnalysisController::class, 'deleteKriteria'])->name('spk.delete-kriteria');

        // SPK Analysis - Prodi Alternatives (Admin Only)
        Route::get('/spk/prodi', [App\Http\Controllers\ProdiAnalysisController::class, 'list'])->name('prodi.list');
        Route::get('/spk/prodi/{prodi}/analysis', [App\Http\Controllers\ProdiAnalysisController::class, 'index'])->name('prodi.analysis');
        Route::post('/spk/prodi/{prodi}/pairwise', [App\Http\Controllers\ProdiAnalysisController::class, 'storePairwise'])->name('prodi.store-pairwise');
        Route::post('/spk/prodi/{prodi}/initialize', [App\Http\Controllers\ProdiAnalysisController::class, 'initializeAlternatives'])->name('prodi.initialize-alternatives');
        Route::put('/spk/prodi/{prodi}/alternative/{alt}', [App\Http\Controllers\ProdiAnalysisController::class, 'updateAlternative'])->name('prodi.update-alternative');
        Route::delete('/spk/prodi/{prodi}/alternative/{alt}', [App\Http\Controllers\ProdiAnalysisController::class, 'deleteAlternative'])->name('prodi.delete-alternative');

        // SPK Analysis - Minat Categories (Admin Only)
        Route::get('/spk/minat', [App\Http\Controllers\MinatAnalysisController::class, 'index'])->name('spk.minat.index');
        Route::post('/spk/minat/initialize', [App\Http\Controllers\MinatAnalysisController::class, 'initialize'])->name('spk.minat.initialize');
        Route::post('/spk/minat/pairwise', [App\Http\Controllers\MinatAnalysisController::class, 'storePairwise'])->name('spk.minat.store-pairwise');
        Route::post('/spk/minat/apply-weights', [App\Http\Controllers\MinatAnalysisController::class, 'applyWeights'])->name('spk.minat.apply-weights');

        // SPK Analysis - Bakat Categories (Admin Only)
        Route::get('/spk/bakat', [App\Http\Controllers\BakatAnalysisController::class, 'index'])->name('spk.bakat.index');
        Route::post('/spk/bakat/initialize', [App\Http\Controllers\BakatAnalysisController::class, 'initialize'])->name('spk.bakat.initialize');
        Route::post('/spk/bakat/pairwise', [App\Http\Controllers\BakatAnalysisController::class, 'storePairwise'])->name('spk.bakat.store-pairwise');
        Route::post('/spk/bakat/apply-weights', [App\Http\Controllers\BakatAnalysisController::class, 'applyWeights'])->name('spk.bakat.apply-weights');

        // SPK Analysis - Nilai Akademik Categories (Admin Only)
        Route::get('/spk/akademik', [App\Http\Controllers\AkademikAnalysisController::class, 'index'])->name('spk.akademik.index');
        Route::post('/spk/akademik/initialize', [App\Http\Controllers\AkademikAnalysisController::class, 'initialize'])->name('spk.akademik.initialize');
        Route::post('/spk/akademik/pairwise', [App\Http\Controllers\AkademikAnalysisController::class, 'storePairwise'])->name('spk.akademik.store-pairwise');
        Route::post('/spk/akademik/apply-weights', [App\Http\Controllers\AkademikAnalysisController::class, 'applyWeights'])->name('spk.akademik.apply-weights');

        // SPK Analysis - Prospek Karir Categories (Admin Only)
        Route::get('/spk/karir', [App\Http\Controllers\KarirAnalysisController::class, 'index'])->name('spk.karir.index');
        Route::post('/spk/karir/initialize', [App\Http\Controllers\KarirAnalysisController::class, 'initialize'])->name('spk.karir.initialize');
        Route::post('/spk/karir/pairwise', [App\Http\Controllers\KarirAnalysisController::class, 'storePairwise'])->name('spk.karir.store-pairwise');
        Route::post('/spk/karir/apply-weights', [App\Http\Controllers\KarirAnalysisController::class, 'applyWeights'])->name('spk.karir.apply-weights');

        // Reports (Admin Only)
        Route::get('/reports/spk-analysis', [ReportsController::class, 'spkAnalysis'])->name('reports.spk-analysis');
        Route::get('/reports/spk-analysis/data', [ReportsController::class, 'getSpkAnalysisData'])->name('reports.spk-analysis.data');
        Route::get('/reports/spk-analysis/{id}/detail', [ReportsController::class, 'getSpkAnalysisDetail'])->name('reports.spk-analysis.detail');
    });

    // SPK Analysis History (accessible by siswa)
    Route::middleware(['check.role:siswa'])->group(function () {
        Route::get('/spk/history', [App\Http\Controllers\SiswaDashboardController::class, 'history'])->name('spk.history');
        Route::get('/spk/result/{id}/export-pdf', [App\Http\Controllers\SiswaDashboardController::class, 'exportPdf'])->name('spk.export-pdf');
        Route::get('/spk/result/{id}/view-pdf', [App\Http\Controllers\SiswaDashboardController::class, 'viewPdf'])->name('spk.view-pdf');
        Route::get('/spk/result/{id}/rekomendasi', [App\Http\Controllers\SiswaDashboardController::class, 'getRekomendasiProdi'])->name('spk.rekomendasi');
        Route::get('/spk/result/{id}/detail', [App\Http\Controllers\SiswaDashboardController::class, 'getDetailAnalysis'])->name('spk.detail');
    });

    // Prodi View (accessible by all authenticated users - Read Only)
    Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.index');
    Route::get('/prodi/{prodi}', [ProdiController::class, 'show'])->name('prodi.show');

    // In-app Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.mark');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAll');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread');

    // Reports
    Route::get('/reports/prodi', [ReportController::class, 'prodi'])->name('reports.prodi');
    Route::get('/reports/prodi/data', [ReportController::class, 'prodiData'])->name('reports.prodi.data');
    Route::get('/reports/prodi/export', [ReportController::class, 'prodiExport'])->name('reports.prodi.export');
});
