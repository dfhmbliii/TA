<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailTitle;
    public $mailMessage;
    public $userName;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct(string $title, string $message, string $userName = '', string $type = 'system')
    {
        $this->mailTitle = $title;
        $this->mailMessage = $message;
        $this->userName = $userName;
        $this->type = $type;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->mailTitle)
                    ->view('emails.announcement');
    }
}
