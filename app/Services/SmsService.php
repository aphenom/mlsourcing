<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected string $apiUrl = 'https://smspro.smartsmsgroup.com/api/api_http.php';
    protected string $username = 'ML SOURCING';
    protected string $password = 'MLSOURCING123';
    protected string $sender = 'ML SOURCING';

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
