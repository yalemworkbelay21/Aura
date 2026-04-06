<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Admin - Signup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">
    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes glow {
            0% { box-shadow: 0 0 5px rgba(228, 197, 144, 0.2); }
            50% { box-shadow: 0 0 20px rgba(228, 197, 144, 0.4); }
            100% { box-shadow: 0 0 5px rgba(228, 197, 144, 0.2); }
        }
        @keyframes slowPan {
            0% { background-position: 0% 50%; background-size: 120%; }
            50% { background-position: 100% 50%; background-size: 140%; }
            100% { background-position: 0% 50%; background-size: 120%; }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-8px); }
            40%, 80% { transform: translateX(8px); }
        }
        @keyframes checkmark {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
        body { 
            margin: 0;
            background: url('./assets/images/login-bg.png') no-repeat center center;
            background-size: 120%;
            animation: slowPan 20s ease-in-out infinite;
            color: white; 
            display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'DM Sans', sans-serif; overflow: hidden;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 0;
        }
        .auth-card { background: rgba(22, 23, 24, 0.85); backdrop-filter: blur(12px); padding: 40px; border-radius: 16px; border: 1px solid rgba(228, 197, 144, 0.3); width: 420px; animation: fadeUp 0.8s ease-out; box-shadow: 0 20px 50px rgba(0,0,0,0.5); position: relative; z-index: 1; }
        .auth-logo { font-family: 'Forum', serif; color: #E4C590; font-size: 28px; text-align: center; margin-bottom: 8px; letter-spacing: 3px; text-transform: uppercase; }
        .auth-subtitle { text-align: center; color: #888; font-size: 14px; margin-bottom: 25px; }
        .input-group { margin-bottom: 18px; position: relative; }
        .input-label { font-size: 12px; color: #888; margin-bottom: 6px; display: block; text-transform: uppercase; letter-spacing: 1px; }
        .auth-input { width: 100%; padding: 13px; background: #1a1a1a; border: 1px solid #333; color: white; border-radius: 8px; outline: none; transition: 0.4s; box-sizing: border-box; font-size: 15px; }
        .auth-input:focus { border-color: #E4C590; box-shadow: 0 0 15px rgba(228, 197, 144, 0.2); }
        .auth-input.error { border-color: #ff6b6b; }
        .auth-input.valid { border-color: #51cf66; }
        .field-hint { font-size: 11px; color: #666; margin-top: 4px; }
        .field-hint.error { color: #ff6b6b; }
        .auth-btn { width: 100%; padding: 14px; background: #E4C590; color: #0a0b0c; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 15px; transition: 0.4s; letter-spacing: 1px; margin-top: 5px; }
        .auth-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(228, 197, 144, 0.3); }
        .auth-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .auth-links { margin-top: 22px; text-align: center; font-size: 14px; color: #a9a9a9; }
        .auth-links a { color: #E4C590; text-decoration: none; transition: 0.3s; }
        .auth-links a:hover { color: white; text-decoration: underline; }
        .error-msg { color: #ff6b6b; font-size: 13px; text-align: center; margin-bottom: 15px; animation: shake 0.4s ease-out; }
        .success-msg { color: #51cf66; font-size: 14px; text-align: center; margin-bottom: 15px; }
        .success-overlay { display: none; position: absolute; inset: 0; background: rgba(22,23,24,0.95); border-radius: 16px; z-index: 10; flex-direction: column; align-items: center; justify-content: center; }
        .success-overlay.show { display: flex; }
        .success-checkmark { width: 80px; height: 80px; border-radius: 50%; background: #51cf66; display: flex; align-items: center; justify-content: center; animation: checkmark 0.6s ease-out; margin-bottom: 20px; }
        .success-checkmark::after { content: '✓'; font-size: 40px; color: white; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">CREATE ACCOUNT</div>
        <p class="auth-subtitle">Sign up for admin access</p>
        <div id="formMsg"></div>
        <form onsubmit="handleSignup(event)" id="signupForm">
            <div class="input-group">
                <label class="input-label">Full Name</label>
                <input type="text" id="username" class="auth-input" placeholder="e.g. Manager Name" required minlength="2">
                <div class="field-hint" id="nameHint"></div>
            </div>
            <div class="input-group">
                <label class="input-label">Email Address</label>
                <input type="email" id="email" class="auth-input" placeholder="your@email.com" required>
                <div class="field-hint" id="emailHint"></div>
            </div>
            <div class="input-group">
                <label class="input-label">Password</label>
                <input type="password" id="password" class="auth-input" placeholder="Minimum 6 characters" required minlength="6">
                <div class="field-hint" id="passHint"></div>
            </div>
            <button type="submit" class="auth-btn" id="signupBtn">CREATE ACCOUNT</button>
        </form>
        <div class="auth-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>

        <!-- Success overlay -->
        <div class="success-overlay" id="successOverlay">
            <div class="success-checkmark"></div>
            <div style="color:#51cf66; font-size:20px; font-weight:bold; margin-bottom:8px;">Account Created!</div>
            <div style="color:#888; font-size:14px;">Redirecting to login...</div>
        </div>
    </div>

    <script>
        // Real-time validation
        const emailInput = document.getElementById('email');
        const passInput = document.getElementById('password');
        const nameInput = document.getElementById('username');

        emailInput.addEventListener('blur', () => {
            const v = emailInput.value.trim();
            const hint = document.getElementById('emailHint');
            if (v && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
                emailInput.classList.add('error');
                emailInput.classList.remove('valid');
                hint.textContent = 'Please enter a valid email (e.g. user@gmail.com)';
                hint.classList.add('error');
            } else if (v) {
                emailInput.classList.remove('error');
                emailInput.classList.add('valid');
                hint.textContent = '';
                hint.classList.remove('error');
            }
        });

        passInput.addEventListener('input', () => {
            const hint = document.getElementById('passHint');
            if (passInput.value.length > 0 && passInput.value.length < 6) {
                hint.textContent = `${6 - passInput.value.length} more characters needed`;
                hint.classList.add('error');
                passInput.classList.add('error');
                passInput.classList.remove('valid');
            } else if (passInput.value.length >= 6) {
                hint.textContent = '✓ Strong enough';
                hint.classList.remove('error');
                hint.style.color = '#51cf66';
                passInput.classList.remove('error');
                passInput.classList.add('valid');
            } else {
                hint.textContent = '';
            }
        });

        async function handleSignup(e) {
            e.preventDefault();
            const username = nameInput.value.trim();
            const email = emailInput.value.trim();
            const password = passInput.value;
            const btn = document.getElementById('signupBtn');
            const msgEl = document.getElementById('formMsg');

            // Client-side validation
            if (username.length < 2) {
                msgEl.innerHTML = '<div class="error-msg">Name must be at least 2 characters.</div>';
                return;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                msgEl.innerHTML = '<div class="error-msg">Please enter a valid email address.</div>';
                return;
            }
            if (password.length < 6) {
                msgEl.innerHTML = '<div class="error-msg">Password must be at least 6 characters.</div>';
                return;
            }

            btn.disabled = true;
            btn.textContent = 'CREATING...';
            msgEl.innerHTML = '';

            try {
                const response = await fetch('auth_api.php?action=signup', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ username, email, password })
                });
                const data = await response.json();

                if (data.success) {
                    document.getElementById('successOverlay').classList.add('show');
                    setTimeout(() => window.location.href = 'login.php', 2500);
                } else {
                    msgEl.innerHTML = `<div class="error-msg">${data.message}</div>`;
                    btn.disabled = false;
                    btn.textContent = 'CREATE ACCOUNT';
                }
            } catch (err) {
                msgEl.innerHTML = '<div class="error-msg">Internal Server Error. Please contact support.</div>';
                btn.disabled = false;
                btn.textContent = 'CREATE ACCOUNT';
            }
        }
    </script>
</body>
</html>
