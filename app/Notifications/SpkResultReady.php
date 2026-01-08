<?php
namespace App\Notifications;

use App\Models\SpkResult;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpkResultReady extends Notification
{
    use Queueable;

    public function __construct(public SpkResult $result) {}

    public function via($notifiable)
    {
        $channels = ['database'];
        if ($notifiable->email_notifications ?? true) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Hasil Analisis SPK Anda Siap')
            ->greeting('Halo ' . ($notifiable->name ?? 'Pengguna'))
            ->line('Hasil analisis SPK terbaru Anda telah tersedia.')
            ->line('Skor total: ' . $this->result->total_score)
            ->line('Kategori: ' . $this->result->category)
            ->action('Lihat Riwayat', url('/riwayat'))
            ->line('Terima kasih telah menggunakan Pilihanku!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'spk_result',
            'title' => 'Hasil Analisis SPK Siap',
            'message' => 'Skor: ' . $this->result->total_score . ' • ' . $this->result->category,
            'result_id' => $this->result->id,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
