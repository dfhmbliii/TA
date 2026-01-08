<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Notify;
use Illuminate\Support\Facades\Auth;

class SystemAnnouncementController extends Controller
{
    public function form()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') { abort(403); }
        return view('admin.announcement');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') { abort(403); }
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'message' => 'required|string|max:1000',
            'send_email' => 'nullable|boolean'
        ]);
        $total = Notify::broadcast($validated['title'], $validated['message'], (bool)$request->send_email);
        return redirect()->route('admin.announcement.form')->with('success', 'Pengumuman dikirim ke ' . $total . ' pengguna.');
    }
}
