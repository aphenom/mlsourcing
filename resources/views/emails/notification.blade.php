<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $texts[array_key_first($texts)]['subject'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; color: #333; }
        .email-container { max-width: 600px; margin: 24px auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,.1); overflow: hidden; }
        .email-header { background-color: #00A752; text-align: center; padding: 22px 20px; }
        .email-header img { max-height: 40px; }
        .email-header h1 { margin: 8px 0 0; font-size: 18px; color: #fff; font-weight: 400; letter-spacing: .5px; }
        .locale-block { padding: 24px 28px 16px; }
        .locale-badge { display: inline-block; background: #e8f7ef; color: #00A752; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; padding: 2px 8px; border-radius: 3px; margin-bottom: 10px; }
        .locale-block h2 { font-size: 17px; margin: 0 0 10px; color: #1a1a1a; }
        .locale-block p { font-size: 15px; line-height: 1.75; margin: 0; color: #444; white-space: pre-line; }
        .separator { border: none; border-top: 1px dashed #e0e0e0; margin: 0 28px; }
        .cta-wrapper { text-align: center; padding: 24px 28px; }
        .action-button { display: inline-block; background-color: #00A752; color: #fff !important; text-decoration: none; padding: 12px 32px; font-size: 15px; border-radius: 5px; font-weight: 600; }
        .action-button:hover { background-color: #009146; }
        .email-footer { text-align: center; background-color: #f4f4f4; padding: 16px 20px; font-size: 13px; color: #777; }
        .email-footer a { color: #00A752; text-decoration: none; }
    </style>
</head>
<body>
<div class="email-container">

    <div class="email-header">
        <h1>ML Sourcing</h1>
    </div>

    @foreach ($texts as $locale => $t)
        @if (!$loop->first)
            <hr class="separator">
        @endif
        <div class="locale-block">
            @if (count($texts) > 1)
                <span class="locale-badge">{{ strtoupper($locale) }}</span>
            @endif
            <h2>{{ $t['subject'] }}</h2>
            <p>{{ $t['message'] }}</p>
        </div>
    @endforeach

    <div class="cta-wrapper">
        <a href="{{ $link }}" class="action-button">
            {{ count($texts) > 1 ? 'Accéder / Access' : (array_key_exists('fr', $texts) ? 'Accéder' : 'Access') }}
        </a>
    </div>

    <div class="email-footer">
        <p>Pour toute assistance / For support:
            <a href="mailto:support@mlsourcing.net">support@mlsourcing.net</a>
        </p>
        <p style="margin-top:6px;font-size:12px;color:#aaa;">© {{ date('Y') }} ML Sourcing. All Rights Reserved.</p>
    </div>

</div>
</body>
</html>
