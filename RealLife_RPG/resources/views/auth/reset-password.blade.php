<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - RealLife RPG</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #0f172a; /* Slate 900 */
            color: #f8fafc; /* Slate 50 */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background-color: #1e293b; /* Slate 800 */
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            border: 1px solid #334155; /* Slate 700 */
        }
        h2 {
            margin-top: 0;
            color: #f8fafc;
            text-align: center;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }
        .subtitle {
            text-align: center;
            color: #94a3b8; /* Slate 400 */
            margin-bottom: 2rem;
            font-size: 0.875rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #cbd5e1; /* Slate 300 */
            margin-bottom: 0.5rem;
        }
        input {
            width: 100%;
            padding: 0.75rem;
            background-color: #334155; /* Slate 700 */
            border: 1px solid #475569; /* Slate 600 */
            border-radius: 0.5rem;
            color: #f8fafc;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus {
            outline: none;
            border-color: #6366f1; /* Indigo 500 */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #6366f1; /* Indigo 500 */
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 0.5rem;
        }
        button:hover {
            background-color: #4f46e5; /* Indigo 600 */
        }
        .error-box {
            margin-bottom: 1.5rem;
            padding: 0.75rem;
            background-color: #450a0a; /* Red 950 */
            border: 1px solid #7f1d1d; /* Red 900 */
            border-radius: 0.5rem;
        }
        .error-list {
            margin: 0;
            padding-left: 1.5rem;
            color: #fca5a5; /* Red 300 */
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Set New Password</h2>
        <p class="subtitle">Enter your new password to access your adventure.</p>
        
        @if ($errors->any())
            <div class="error-box">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update.web') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus readonly style="opacity: 0.7; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required placeholder="Min. 8 characters">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter password">
            </div>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
