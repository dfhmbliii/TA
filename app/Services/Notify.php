<?php
namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Mail\AnnouncementMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Notify
{
    /**
     * Create an in-app notification and optionally send email (simple).
     */
    public static function send(User $user, string $type, string $title, string $message, array $data = [], bool $emailIfPref = false): Notification
    {
        $record = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        if ($emailIfPref && ($user->email_notifications ?? false)) {
            try {
                Mail::to($user->email)->send(
                    new AnnouncementMail($title, $message, $user->name, $type)
                );
            } catch (\Throwable $e) {
                Log::warning('Email notify failed: '.$e->getMessage());
            }
        }

        return $record;
    }

    /**
     * Broadcast system announcement to all users.
     */
    public static function broadcast(string $title, string $message, bool $email = false): int
    {
        $count = 0;
        User::chunk(200, function($users) use (&$count, $title, $message, $email) {
            foreach ($users as $user) {
                self::send($user, 'system', $title, $message, [], $email);
                $count++;
            }
        });
        return $count;
    }
}
