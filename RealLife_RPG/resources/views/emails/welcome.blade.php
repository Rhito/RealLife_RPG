<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4A90E2; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 5px 5px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #4A90E2; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to RealLife RPG!</h1>
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            <p>Welcome to <strong>RealLife RPG</strong>! We're excited to have you join our community of productivity heroes.</p>
            <p>You've started your journey with:</p>
            <ul>
                <li><strong>500 Coins</strong></li>
                <li><strong>500 XP</strong></li>
                <li><strong>50 HP</strong></li>
            </ul>
            <p>Start completing tasks, earning rewards, and leveling up your life today!</p>
            <center>
                <a href="#" class="button">Open App</a>
            </center>
            <p>Best regards,<br>The RealLife RPG Team</p>
        </div>
    </div>
</body>
</html>
