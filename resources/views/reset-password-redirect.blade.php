<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - RealLife RPG</title>
    <meta http-equiv="refresh" content="3;url={{ $deepLink }}">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
        }
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        h1 {
            color: #333;
            margin-bottom: 15px;
            font-size: 24px;
        }
        p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: background 0.3s;
            margin: 10px 0;
        }
        .button:hover {
            background: #5568d3;
        }
        .note {
            margin-top: 30px;
            font-size: 14px;
            color: #999;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        .spinner {
            width: 40px;
            height: 40px;
            margin: 20px auto;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔐</div>
        <h1>Password Reset - Mobile App</h1>
        <p>Opening RealLife RPG app...</p>
        <div class="spinner"></div>
        
        <p>If the app doesn't open automatically, tap the button below:</p>
        <a href="{{ $deepLink }}" class="button">Open App</a>
        
        <div class="note">
            <strong>📱 Mobile App Required</strong><br>
            This password reset link is designed for the RealLife RPG mobile app. 
            If you haven't installed the app yet, please download it first.
        </div>
    </div>
    
    <script>
        // Try to open the app immediately
        window.location.href = '{{ $deepLink }}';
    </script>
</body>
</html>
