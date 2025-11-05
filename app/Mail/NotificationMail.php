<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $link;
    /**
     * Create a new message instance.
     */
    public function __construct($subject,$message,$link)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.notification')
            ->with([
                'messageContent' => $this->message,
                'link' => $this->link,
            ]);
    }

}
