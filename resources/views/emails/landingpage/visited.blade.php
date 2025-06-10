<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page Visit Notification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #222; }
        p { margin-bottom: 10px; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Landing Page Visited</h1>
        <p>A user has visited the landing page.</p>
        <ul>
            <li><strong>IP Address:</strong> {{ $visitData['ip_address'] }}</li>
            <li><strong>User Agent:</strong> {{ $visitData['user_agent'] }}</li>
            <li><strong>Time of Visit:</strong> {{ $visitData['visited_at'] }}</li>
        </ul>
        <p class="footer">This is an automated notification.</p>
    </div>
</body>
</html>
