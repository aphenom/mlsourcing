<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReclamationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $type;
    public $messageContent;
    public $relatedItem;
    public $sellerMail;

    public function __construct($type, $messageContent, $relatedItem, $sellerMail)
    {
        $this->type = $type;
        $this->messageContent = $messageContent;
        $this->relatedItem = $relatedItem;
        $this->sellerMail = $sellerMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Reclamation: {$this->type}")
                    ->view('emails.reclamation')
                    ->with([
                        'type' => $this->type,
                        'messageContent' => $this->messageContent,
                        'relatedItem' => $this->relatedItem,
                        'sellerMail' => $this->sellerMail,
                    ]);
    }
}
