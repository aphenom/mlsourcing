<?php

namespace App\Services;

use App\Mail\NotificationMail;
use App\Models\User;
use App\Notifications\SmsNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Languages sent in every bilingual email/SMS, in priority order.
     * French first since it is the platform's primary language.
     */
    private static array $locales = ['fr', 'en'];

    // ─────────────────────────────────────────────────────────────────────────
    // Public API
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Send a full notification: database record + optional email + optional SMS.
     * All text is bilingual (all configured locales in a single email/SMS).
     *
     * @param User     $user      Recipient user model
     * @param int|null $requestId Associated request ID stored in the DB notification
     * @param string   $key       Base key in lang/xx/notifications.php (e.g. 'payment_approved_seller')
     * @param array    $params    Named parameters for the translation strings (e.g. ['payment_id' => 5])
     * @param string   $link      Action URL shown in the email CTA button
     * @param array    $channels  Channels to use — any combination of 'db', 'mail', 'sms'
     */
    public static function notify(
        User $user,
        ?int $requestId,
        string $key,
        array $params = [],
        string $link = '#',
        array $channels = ['db', 'mail', 'sms']
    ): void {
        $texts = self::buildTexts($key, $params);

        if (in_array('db', $channels)) {
            self::dispatchDb($user, $requestId, $texts, $link);
        }

        if (in_array('mail', $channels) && self::isMailConfigured()) {
            self::dispatchMail($user->email, $texts, $link);
        }

        if (in_array('sms', $channels) && self::isSmsConfigured() && !empty($user->phone_number)) {
            // Concatenate all locale SMS texts so recipients see both languages.
            $smsText = implode(' | ', array_column($texts, 'sms'));
            self::dispatchSms($user->phone_number, $smsText);
        }
    }

    /**
     * Send a welcome / credential email only (no DB record, no SMS — credentials must not appear in SMS).
     */
    public static function sendWelcomeMail(string $to, string $name, string $email, string $plainPassword): void
    {
        if (!self::isMailConfigured()) return;

        $texts = self::buildTexts('welcome', [
            'name'     => $name,
            'email'    => $email,
            'password' => $plainPassword,
        ]);

        self::dispatchMail($to, $texts, route('login'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Configuration guards
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Returns true only when SMTP mail is set up with a real host.
     * Falls back to false for the 'log' / 'array' / 'null' drivers used in local dev.
     */
    public static function isMailConfigured(): bool
    {
        $mailer = config('mail.default', 'log');
        if (in_array($mailer, ['log', 'array', 'null'])) {
            return false;
        }
        return !empty(config('mail.mailers.smtp.host'));
    }

    /**
     * Returns true only when SMS API credentials are present in the environment.
     */
    public static function isSmsConfigured(): bool
    {
        return !empty(config('services.sms.username'))
            && !empty(config('services.sms.password'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Build translated text arrays for every configured locale.
     * Restores the application locale afterward.
     *
     * @return array<string, array{subject: string, message: string, sms: string}>
     */
    private static function buildTexts(string $key, array $params): array
    {
        $original = App::getLocale();
        $texts    = [];

        foreach (self::$locales as $locale) {
            App::setLocale($locale);
            $texts[$locale] = [
                'subject' => __('notifications.' . $key . '_subject', $params),
                'message' => __('notifications.' . $key . '_message', $params),
                'sms'     => __('notifications.' . $key . '_sms',     $params),
            ];
        }

        App::setLocale($original);
        return $texts;
    }

    private static function dispatchDb(User $user, ?int $requestId, array $texts, string $link): void
    {
        // Build bilingual subject: "Objet FR / EN Subject"
        $subject = implode(' / ', array_column($texts, 'subject'));

        // Build bilingual message: "[FR] texte\n\n[EN] text"
        $messageParts = [];
        foreach ($texts as $locale => $t) {
            $messageParts[] = '[' . strtoupper($locale) . '] ' . $t['message'];
        }
        $message = implode("\n\n", $messageParts);

        try {
            $user->notify(new UserNotification($requestId, $subject, $message, $link));
        } catch (\Exception $e) {
            Log::error("DB notification failed for user {$user->id}: " . $e->getMessage());
        }
    }

    private static function dispatchMail(string $to, array $texts, string $link): void
    {
        try {
            Mail::to($to)->send(new NotificationMail($texts, $link));
        } catch (\Exception $e) {
            Log::error("Email notification failed to {$to}: " . $e->getMessage());
        }
    }

    private static function dispatchSms(string $phone, string $message): void
    {
        try {
            Notification::route('sms', [$phone])->notify(new SmsNotification([$phone], $message));
        } catch (\Exception $e) {
            Log::error("SMS notification failed to {$phone}: " . $e->getMessage());
        }
    }
}
