<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/login.css', 'resources/css/password.css', 'resources/js/password.js'])
    <title>Login - DataCenterRMS</title>
</head>
<body>
   <div class="login-card">
    <h1>AlphaFold Data Center</h1>
    <h2>Login</h2>
    
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
    <div class="bubble bubble-3"></div>
    <div class="bubble bubble-4"></div>

    <form action="{{ route('login') }}" method="POST">
        @csrf {{-- Mandatory security token to prevent 419 errors --}}

        <div class="input-group">
            <label for="email">Email Address: </label>
            <input 
                type="email" 
                name="email" {{-- Must match the key in your AuthController --}}
                id="email" 
                placeholder="Enter your email" 
                value="{{ old('email') }}" {{-- Retains the email if the login attempt fails --}}
                required
            >
            
            {{-- Laravel Server-side Error --}}
            @error('email')
                <span class="error-text" style="display:block; color: #ff4d4d; font-size: 0.8rem; margin-top: 5px;">
                    {{ $message }}
                </span>
            @enderror
            
            {{-- Your Original JS Error Span --}}
            <span id="userError" class="error-text">Please enter a valid email address.</span>
        </div>

        <div class="input-group">
            <label for="password">Password: </label>
            <div class="password-wrapper">
                <input 
                    type="password" 
                    name="password" {{-- Must match the key in your AuthController --}}
                    id="password" 
                    placeholder="Min. 8 characters" 
                    required
                >
                <button type="button" id="togglePassword" class="toggle-btn">
                    <span id="eyeIcon">👁️</span>
                </button>
            </div>   
            
            {{-- Laravel Server-side Error --}}
            @error('password')
                <span class="error-text" style="display:block; color: #ff4d4d; font-size: 0.8rem; margin-top: 5px;">
                    {{ $message }}
                </span>
            @enderror

            {{--Original JS Error Span --}}
            <span id="passwordError" class="error-text">Password must be at least 8 characters.</span> 
        </div>

        <div class="button-group">
            <button type="submit" class="btn-signin">Sign in</button>
            <a href="{{ route('register') }}" class="btn-create">Create Account</a>
        </div>
    </form>
</div>
</body>
</html>