<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Neuro Haven</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: var(--space-4);
        }
        .auth-container {
            width: 100%;
            max-width: 400px;
        }
        .auth-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: var(--space-8);
        }
        .auth-card h1 {
            text-align: center;
            margin-bottom: var(--space-6);
            font-size: 1.75rem;
        }
        .form-group {
            margin-bottom: var(--space-4);
        }
        .form-group label {
            display: block;
            margin-bottom: var(--space-2);
            font-weight: 500;
            color: var(--color-text);
        }
        .form-group input {
            width: 100%;
            padding: var(--space-3) var(--space-4);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: 1rem;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(29, 159, 118, 0.1);
        }
        .error-message {
            color: var(--color-danger);
            font-size: 0.875rem;
            margin-top: var(--space-2);
        }
        .btn-submit {
            width: 100%;
            padding: var(--space-3);
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background: var(--color-primary-strong);
        }
        .auth-footer {
            text-align: center;
            margin-top: var(--space-6);
            color: var(--color-text-muted);
        }
        .auth-footer a {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 600;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .alert {
            padding: var(--space-3);
            margin-bottom: var(--space-4);
            border-radius: var(--radius-md);
        }
        .alert-success {
            background: rgba(29, 159, 118, 0.1);
            color: var(--color-primary-strong);
            border: 1px solid var(--color-primary);
        }
        .alert-error {
            background: rgba(194, 65, 12, 0.1);
            color: var(--color-danger);
            border: 1px solid var(--color-danger);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1>Login</h1>

            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Sign up</a>
            </div>
        </div>
    </div>
</body>
</html>