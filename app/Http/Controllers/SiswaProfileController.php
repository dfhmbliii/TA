<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Siswa;

class SiswaProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function showProfile()
    {
        $user = Auth::user();
        $siswa = Siswa::where('email', $user->email)->first();

        return view('siswa.profile', [
            'siswa' => $siswa,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $siswa = Siswa::where('email', $user->email)->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
                'unique:siswas,email,' . optional($siswa)->id,
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:500'],
            'nisn' => ['required', 'string', 'max:50', 'unique:siswas,nisn,' . optional($siswa)->id],
            'jurusan_sma' => ['required', 'string', 'max:255'],
            'asal_sekolah' => ['required', 'string', 'max:255'],
            'tahun_lulus' => ['required', 'digits:4'],
        ]);

        $userFields = ['name', 'email', 'phone', 'birth_date', 'address', 'bio'];
        $user->fill(array_intersect_key($validated, array_flip($userFields)));
        $user->save();

        $siswaData = collect($validated)->only([
            'name',
            'nisn',
            'email',
            'jurusan_sma',
            'asal_sekolah',
            'tahun_lulus',
        ])->toArray();

        if ($siswa) {
            $siswa->update($siswaData);
        } else {
            Siswa::create($siswaData);
        }

        return redirect()->route('siswa.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show the settings page.
     */
    public function showSettings()
    {
        return view('siswa.settings');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()->route('siswa.settings')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Update notification settings.
     */
    public function updateNotifications(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->email_notifications = $request->has('email_notifications');
        $user->spk_updates = $request->has('spk_updates');
        $user->system_announcements = $request->has('system_announcements');
        $user->save();

        return redirect()->route('siswa.settings')->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
    }

    /**
     * Request account deletion (need admin approval).
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        
        // Check if already have pending request
        $existingRequest = \App\Models\AccountDeletionRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
            
        if ($existingRequest) {
            return redirect()->back()->with('error', 'Anda sudah memiliki permintaan hapus akun yang sedang diproses.');
        }

        // Create deletion request
        \App\Models\AccountDeletionRequest::create([
            'user_id' => $user->id,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);
        
        // Send notification to admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'title' => 'Permintaan Hapus Akun',
                'message' => 'User ' . $user->name . ' mengajukan permintaan hapus akun.',
                'type' => 'warning',
                'is_read' => false
            ]);
        }

        return redirect()->back()->with('success', 'Permintaan hapus akun telah dikirim ke admin untuk ditinjau.');
    }
}
