<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $texts;
    public string $link;

    /**
     * @param array<string, array{subject: string, message: string, sms: string}> $texts
     *        Keyed by locale, e.g. ['fr' => ['subject'=>..., 'message'=>..., 'sms'=>...], 'en' => [...]]
     * @param string $link  Action URL for the CTA button
     */
    public function __construct(array $texts, string $link)
    {
        $this->texts = $texts;
        $this->link  = $link;
    }

    public function build(): self
    {
        $firstLocale = array_key_first($this->texts);
        return $this->subject($this->texts[$firstLocale]['subject'])
                    ->view('emails.notification');
    }
}
