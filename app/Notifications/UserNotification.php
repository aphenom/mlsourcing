<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    use Queueable;

    public $requestID;
    public $subject;
    public $message;
    public $link;

    /**
     * Create a new notification instance.
     */
    public function __construct($requestID, $subject, $message, $link)
    {
        $this->requestID = $requestID;  // Fixed the assignment
        $this->subject = $subject;
        $this->message = $message;
        $this->link = $link;
    }

    /**
     * Determine the channels the notification will be sent through.
     */
    public function via(object $notifiable): array
    {
        return ['database'];  // You can add 'mail', 'sms', etc. if needed
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => $this->subject,
            'message' => $this->message,
            'link' => $this->link,
            'requestID' => $this->requestID,
        ];
    }
}
