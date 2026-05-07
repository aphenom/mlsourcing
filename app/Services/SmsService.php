<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected string $apiUrl;
    protected string $username;
    protected string $password;
    protected string $sender;

    public function __construct()
    {
        $this->apiUrl = config('services.sms.url', 'https://smspro.smartsmsgroup.com/api/api_http.php');
        $this->username = config('services.sms.username', '');
        $this->password = config('services.sms.password', '');
        $this->sender = config('services.sms.sender', '');
    }

    public function sendSms(array $recipients, string $message, string $datetime)
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'sender' => $this->sender,
            'text' => $message,
            'type' => 'text',
            'datetime' => $datetime,
        ];

        $postFields = 'to=' . implode(';', $recipients);
        foreach ($params as $key => $value) {
            $postFields .= '&' . $key . '=' . rawurlencode($value);
        }

        $response = Http::asForm()
            ->withHeaders(['Connection' => 'close'])
            ->post($this->apiUrl, $postFields);

        if ($response->successful()) {
            return $response->body();
        }

        return $response->status() . ' - ' . $response->body();
    }
}
