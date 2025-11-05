<?php

namespace App\Notifications;

use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SmsNotification extends Notification
{
    use Queueable;

    protected array $recipients;
    protected string $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $recipients,string $message)
    {
        $this->recipients = $recipients;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return [];
    }

    public function toSms(SmsService $smsService)
    {
        $datetime = now()->format('Y-m-d H:i:s');
        return $smsService->sendSms($this->recipients, $this->message, $datetime);
    }



}
