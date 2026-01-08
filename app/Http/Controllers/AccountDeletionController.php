<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountDeletionRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class AccountDeletionController extends Controller
{
    /**
     * Display list of account deletion requests (Admin only)
     */
    public function index()
    {
        $requests = AccountDeletionRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.account-deletion-requests', compact('requests'));
    }
    
    /**
     * Approve deletion request
     */
    public function approve(Request $request, $id)
    {
        $deletionRequest = AccountDeletionRequest::findOrFail($id);
        
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);
        
        // Update request status
        $deletionRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->admin_notes,
            'approved_at' => now()
        ]);
        
        // Notify user
        Notification::create([
            'user_id' => $deletionRequest->user_id,
            'title' => 'Permintaan Hapus Akun Disetujui',
            'message' => 'Permintaan hapus akun Anda telah disetujui. Akun Anda akan segera dihapus.',
            'type' => 'danger',
            'is_read' => false
        ]);
        
        // Delete user account
        $user = User::find($deletionRequest->user_id);
        if ($user) {
            $user->delete();
        }
        
        return redirect()->back()->with('success', 'Permintaan hapus akun telah disetujui dan akun user telah dihapus.');
    }
    
    /**
     * Reject deletion request
     */
    public function reject(Request $request, $id)
    {
        $deletionRequest = AccountDeletionRequest::findOrFail($id);
        
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);
        
        // Update request status
        $deletionRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->admin_notes,
            'approved_at' => now()
        ]);
        
        // Notify user
        Notification::create([
            'user_id' => $deletionRequest->user_id,
            'title' => 'Permintaan Hapus Akun Ditolak',
            'message' => 'Permintaan hapus akun Anda ditolak. Alasan: ' . $request->admin_notes,
            'type' => 'info',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Permintaan hapus akun telah ditolak.');
    }
}
