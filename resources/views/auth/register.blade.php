<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/password.css') }}">
    <script src="{{ asset('js/password.js') }}"></script>
</head>
<body class="auth-page"> 
    <div class="login-card">
        <h1>AlphaFold <span>Data Center</span></h1>
        <h2>Create Account</h2>

        <form action="{{ route('auth.register') }}" method="POST" id="registerForm">
            @csrf

            {{-- 1. Full Name --}}
            <div class="input-group">
                <label>Full Name:</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe">
            </div>

            {{-- 2. Email --}}
            <div class="input-group">
                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="name@company.com" class="@error('email') input-error @enderror">
                @error('email')
                    <span class="error-text active">{{ $message }}</span>
                @enderror
                <span id="emailError" class="error-text">Please enter a valid email address.</span>
            </div>

            {{-- 3. Password --}}
            <div class="input-group">
                <label for="password">Password:</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" required class="@error('password') input-error @enderror">
                    <button type="button" id="togglePassword" class="toggle-btn">
                        <span id="eyeIcon">👁️</span>
                    </button>
                </div>
                @error('password')
                    <span class="error-text active">{{ $message }}</span>
                @enderror
                <span id="passwordError" class="error-text">Password must be at least 8 characters.</span> 
            </div>

            {{-- 4. Justification Box (Moved outside password wrapper) --}}
            <div class="input-group">
                <label>Justification for account request:</label>
                <textarea name="justification" rows="4" placeholder="Explain why you need access to the AlphaFold resources..." required style="width: 100%; border-radius: 8px; border: 1px solid #ddd; padding: 10px; margin-top: 5px;"></textarea>
            </div>

            {{-- 5. Final Register Button --}}
            <button type="submit" class="btn-signin">Submit Request</button>
            
            <p style="margin-top: 15px; font-size: 0.8rem; text-align: center;">
                Already have an account? <a href="{{ route('login') }}" style="color: #0096FF; text-decoration: none; font-weight: 600;">Sign In</a>
            </p>
        </form>
    </div>
</body>
</html>