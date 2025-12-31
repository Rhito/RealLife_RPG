<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #432874;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 { margin-bottom: 10px; }
        p { color: #BBAADD; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Email Verified!</h1>
        <p>Your account is now active.</p>
        <p>You can close this window and return to the app.</p>
    </div>

    <script>
        // Attempt to close the window
        setTimeout(function() {
            window.open('','_self').close();
            // Fallback for some mobile browsers
            window.close();
        }, 2000);
    </script>
</body>
</html>
