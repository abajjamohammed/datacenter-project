document.addEventListener('DOMContentLoaded', () => {
    
    // 1. SELECT ELEMENTS
    const loginForm = document.querySelector('form');
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const emailInput = document.getElementById('email');
    const userError = document.getElementById('userError');
    const passwordError = document.getElementById('passwordError');

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Console Check (Helpful for debugging)
    console.log("Login Script Loaded");

    // 2. PASSWORD VISIBILITY TOGGLE
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function (e) {
            // Stop the button from submitting the form
            e.preventDefault(); 
            e.stopPropagation();

            // Check the current type and flip it
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                if (eyeIcon) eyeIcon.textContent = '👁️‍🗨️';
                
            } else {
                passwordInput.type = 'password';
                if (eyeIcon) eyeIcon.textContent = '👁️';

            }

            // Return focus to input so user can keep typing
            passwordInput.focus();
        });
    } else {
        console.error("Critical Error: Toggle button or Password input not found in HTML.");
    }

    // 3. EMAIL VALIDATION (Real-time)
    if (emailInput && userError) {
        emailInput.addEventListener('input', () => {
            const val = emailInput.value;
            if (val.length > 0) {
                if (emailPattern.test(val)) {
                    emailInput.classList.add('input-success');
                    emailInput.classList.remove('input-error');
                    userError.style.display = 'none';
                } else {
                    emailInput.classList.add('input-error');
                    emailInput.classList.remove('input-success');
                    userError.style.display = 'block';
                    userError.textContent = "Please enter a valid email address.";
                }
            } else {
                emailInput.classList.remove('input-error', 'input-success');
                userError.style.display = 'none';
            }
        });
    }

    // 4. PASSWORD LENGTH VALIDATION (Real-time)
    if (passwordInput && passwordError) {
        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            if (val.length > 0 && val.length < 8) {
                passwordInput.classList.add('input-error');
                passwordInput.classList.remove('input-success');
                passwordError.style.display = 'block';
            } else if (val.length >= 8) {
                passwordInput.classList.remove('input-error');
                passwordInput.classList.add('input-success');
                passwordError.style.display = 'none';
            } else {
                passwordInput.classList.remove('input-error', 'input-success');
                passwordError.style.display = 'none';
            }
        });
    }

    // 5. PREVENT "GHOST" SUBMISSIONS
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (emailInput && !emailPattern.test(emailInput.value)) {
                e.preventDefault(); 
                alert('Please fix the errors before submitting.');
            }
        });
    }
});