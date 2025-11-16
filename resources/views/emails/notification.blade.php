<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333333;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #00A752;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
        }
        .email-body p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }
        .email-footer {
            text-align: center;
            background-color: #f4f4f4;
            padding: 15px;
            font-size: 14px;
            color: #666666;
        }
        .action-button {
            display: inline-block;
            background-color: #00A752;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .action-button:hover {
            background-color: #008f48;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ $subject }}</h1>
        </div>
        <div class="email-body">
            <p>{{ $messageContent }}</p>
            <p style="text-align: center;">
                <a href="{{ $link }}" class="action-button">View Details</a>
            </p>
        </div>
        <div class="email-footer">
            <p>Thank you for choosing our platform!</p>
            <p>For support, contact us at <a href="mailto:support@mlsourcing.net">support@mlsourcing.net</a></p>
        </div>
    </div>
</body>
</html>
