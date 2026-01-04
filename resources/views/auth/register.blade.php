<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    @vite(['resources/css/login.css', 'resources/css/password.css', 'resources/js/password.js'])
</head>
<body class="auth-page"> {{-- Added class for scroll control --}}
    <div class="login-card">
        <h1>AlphaFold <span>Data Center</span></h1>
        <h2>Create Account</h2>

        <form action="{{ route('register.store') }}" method="POST" id="registerForm">
            @csrf

            <div class="input-group">
                <label for="role_id">Account Type:</label>
                <select name="role_id" id="role_id" required class="@error('role_id') input-error @enderror">
                    <option value="" disabled selected>Select your role</option>
                    <option value="1">Guest</option>
                    <option value="2">Internal User</option>
                    <option value="3">Technical Manager</option>
                    <option value="4">Database Administrator</option>
                </select>
                @error('role_id')
                    <span class="error-text active">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label>Full Name:</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe">
            </div>

            <div class="input-group">
                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="name@company.com" class="@error('email') input-error @enderror">
                
                {{-- Server-side Email Error --}}
                @error('email')
                    <span class="error-text active">{{ $message }}</span>
                @enderror
                {{-- JS-side Email Error placeholder if needed --}}
                <span id="emailError" class="error-text">Please enter a valid email address.</span>
            </div>

            <div class="input-group">
                <label for="password">Password:</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" required class="@error('password') input-error @enderror">
                    <button type="button" id="togglePassword" class="toggle-btn">
                        <span id="eyeIcon">👁️</span>
                    </button>
                    
                    {{-- Server-side Password Error --}}
                    @error('password')
                        <span class="error-text active">{{ $message }}</span>
                    @enderror

                    {{-- JS Password Error (connected to password.js) --}}
                    <span id="passwordError" class="error-text">Password must be at least 8 characters.</span> 
                </div>
            </div>

            <button type="submit" class="btn-signin">Register</button>
            
            <p style="margin-top: 15px; font-size: 0.8rem; text-align: center;">
                Already have an account? <a href="{{ route('login') }}" style="color: #0096FF; text-decoration: none; font-weight: 600;">Sign In</a>
            </p>
        </form>
    </div>
</body>
</html>