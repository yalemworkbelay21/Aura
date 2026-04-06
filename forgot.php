<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Admin - Forgot Password</title>
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
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
        .auth-logo { font-family: 'Forum', serif; color: #E4C590; font-size: 28px; text-align: center; margin-bottom: 10px; letter-spacing: 3px; text-transform: uppercase; animation: fadeUp 1s ease-out; }
        .auth-subtitle { text-align: center; color: #888; font-size: 14px; margin-bottom: 30px; }
        .input-group { margin-bottom: 20px; }
        .input-label { font-size: 12px; color: #888; margin-bottom: 6px; display: block; text-transform: uppercase; letter-spacing: 1px; }
        .auth-input { width: 100%; padding: 13px; background: #1a1a1a; border: 1px solid #333; color: white; border-radius: 8px; outline: none; transition: 0.4s; box-sizing: border-box; font-size: 15px; }
        .auth-input:focus { border-color: #E4C590; box-shadow: 0 0 15px rgba(228, 197, 144, 0.2); }
        .auth-btn { width: 100%; padding: 14px; background: #E4C590; color: #0a0b0c; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 15px; transition: 0.4s; letter-spacing: 1px; }
        .auth-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(228, 197, 144, 0.3); }
        .auth-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .auth-links { margin-top: 25px; text-align: center; font-size: 14px; color: #a9a9a9; }
        .auth-links a { color: #E4C590; text-decoration: none; transition: 0.3s; }
        .auth-links a:hover { color: white; text-decoration: underline; }
        .error-msg { color: #ff6b6b; font-size: 13px; text-align: center; margin-bottom: 15px; animation: shake 0.4s ease-out; }
        .success-msg { color: #51cf66; font-size: 13px; text-align: center; margin-bottom: 15px; animation: pulse 0.6s ease-out; }
        .otp-inputs { display: flex; gap: 8px; justify-content: center; margin: 20px 0; }
        .otp-inputs input {
            width: 48px; height: 56px; text-align: center; font-size: 24px; font-weight: bold;
            background: #1a1a1a; border: 2px solid #333; color: #E4C590; border-radius: 10px;
            outline: none; transition: 0.3s;
        }
        .otp-inputs input:focus { border-color: #E4C590; box-shadow: 0 0 12px rgba(228, 197, 144, 0.3); }
        .step { display: none; }
        .step.active { display: block; animation: fadeUp 0.5s ease-out; }
        .timer { text-align: center; color: #888; font-size: 13px; margin-top: 10px; }
        .timer span { color: #E4C590; font-weight: bold; }
        .resend-link { color: #E4C590; cursor: pointer; text-decoration: underline; font-size: 13px; }
        .resend-link:hover { color: white; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">RECOVER ACCESS</div>

        <!-- STEP 1: Enter Email -->
        <div class="step active" id="step1">
            <p class="auth-subtitle">Enter your admin email to receive a verification code</p>
            <div id="msg1"></div>
            <form onsubmit="sendOTP(event)">
                <div class="input-group">
                    <label class="input-label">Email Address</label>
                    <input type="email" id="email" class="auth-input" placeholder="your@email.com" required>
                </div>
                <button type="submit" class="auth-btn" id="sendBtn">SEND OTP CODE</button>
            </form>
        </div>

        <!-- STEP 2: Enter OTP -->
        <div class="step" id="step2">
            <p class="auth-subtitle">Enter the 6-digit code sent to <strong id="emailDisplay"></strong></p>
            <div id="msg2"></div>
            <div class="otp-inputs" id="otpInputs">
                <input type="text" maxlength="1" data-index="0" autofocus>
                <input type="text" maxlength="1" data-index="1">
                <input type="text" maxlength="1" data-index="2">
                <input type="text" maxlength="1" data-index="3">
                <input type="text" maxlength="1" data-index="4">
                <input type="text" maxlength="1" data-index="5">
            </div>
            <div class="timer" id="timerArea">Code expires in <span id="countdown">10:00</span></div>
            <button class="auth-btn" onclick="verifyStep()" id="verifyBtn" style="margin-top:15px;">VERIFY CODE</button>
            <p style="text-align:center; margin-top:12px;">
                <span class="resend-link" onclick="resendOTP()">Resend Code</span>
            </p>
        </div>

        <!-- STEP 3: New Password -->
        <div class="step" id="step3">
            <p class="auth-subtitle">Create a new password for your account</p>
            <div id="msg3"></div>
            <form onsubmit="resetPassword(event)">
                <div class="input-group">
                    <label class="input-label">New Password</label>
                    <input type="password" id="newPassword" class="auth-input" placeholder="Minimum 6 characters" required minlength="6">
                </div>
                <div class="input-group">
                    <label class="input-label">Confirm Password</label>
                    <input type="password" id="confirmPassword" class="auth-input" placeholder="Re-enter password" required minlength="6">
                </div>
                <button type="submit" class="auth-btn">RESET PASSWORD</button>
            </form>
        </div>

        <div class="auth-links">
            <p>Remember it? <a href="login.php">Back to Login</a></p>
        </div>
    </div>

    <script>
        let userEmail = '';
        let userOTP = ''; // For local dev fallback
        let countdownTimer;

        // ---- OTP Input Auto-focus ----
        document.querySelectorAll('.otp-inputs input').forEach((inp, i, arr) => {
            inp.addEventListener('input', (e) => {
                inp.value = inp.value.replace(/[^0-9]/g, '');
                if (inp.value && i < arr.length - 1) arr[i + 1].focus();
            });
            inp.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !inp.value && i > 0) arr[i - 1].focus();
            });
            inp.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                for (let j = 0; j < Math.min(paste.length, 6); j++) {
                    arr[j].value = paste[j];
                }
                if (paste.length >= 6) arr[5].focus();
            });
        });

        function showMsg(elId, text, isError) {
            document.getElementById(elId).innerHTML = `<div class="${isError ? 'error-msg' : 'success-msg'}">${text}</div>`;
        }

        function goToStep(num) {
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.getElementById('step' + num).classList.add('active');
        }

        function startCountdown(seconds) {
            clearInterval(countdownTimer);
            let remaining = seconds;
            const el = document.getElementById('countdown');
            countdownTimer = setInterval(() => {
                remaining--;
                const m = Math.floor(remaining / 60);
                const s = remaining % 60;
                el.textContent = `${m}:${s.toString().padStart(2, '0')}`;
                if (remaining <= 0) {
                    clearInterval(countdownTimer);
                    el.textContent = 'EXPIRED';
                    el.style.color = '#ff6b6b';
                }
            }, 1000);
        }

        async function sendOTP(e) {
            e.preventDefault();
            const email = document.getElementById('email').value.trim();
            const btn = document.getElementById('sendBtn');
            btn.disabled = true;
            btn.textContent = 'SENDING...';
            document.getElementById('msg1').innerHTML = '';

            try {
                const res = await fetch('auth_api.php?action=forgot', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ email })
                });
                const data = await res.json();
                
                if (data.success) {
                    userEmail = email;
                    if (data.dev_otp) {
                        userOTP = data.dev_otp; // Local dev fallback
                        alert("LOCAL TEST MODE: Since you are running on localhost without an SMTP mail server, your OTP is showing here instead: " + data.dev_otp);
                    }
                    document.getElementById('emailDisplay').textContent = email;
                    showMsg('msg1', data.message, false);
                    setTimeout(() => {
                        goToStep(2);
                        startCountdown(600); // 10 minutes
                        document.querySelector('.otp-inputs input').focus();
                    }, 1000);
                } else {
                    showMsg('msg1', data.message, true);
                }
            } catch (err) {
                showMsg('msg1', 'Network error. Please try again.', true);
            }
            btn.disabled = false;
            btn.textContent = 'SEND OTP CODE';
        }

        function getOTPValue() {
            return Array.from(document.querySelectorAll('.otp-inputs input')).map(i => i.value).join('');
        }

        function verifyStep() {
            const otp = getOTPValue();
            if (otp.length !== 6) {
                showMsg('msg2', 'Please enter all 6 digits.', true);
                return;
            }
            // Move to password step — actual verification happens on submit
            goToStep(3);
        }

        async function resetPassword(e) {
            e.preventDefault();
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                showMsg('msg3', 'Passwords do not match.', true);
                return;
            }
            if (newPassword.length < 6) {
                showMsg('msg3', 'Password must be at least 6 characters.', true);
                return;
            }

            const otp = getOTPValue();

            try {
                const res = await fetch('auth_api.php?action=verify_otp', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ email: userEmail, otp: otp, new_password: newPassword })
                });
                const data = await res.json();

                if (data.success) {
                    showMsg('msg3', data.message, false);
                    setTimeout(() => window.location.href = 'login.php', 2000);
                } else {
                    showMsg('msg3', data.message, true);
                }
            } catch (err) {
                showMsg('msg3', 'Network error. Please try again.', true);
            }
        }

        async function resendOTP() {
            const res = await fetch('auth_api.php?action=forgot', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ email: userEmail })
            });
            const data = await res.json();
            if (data.success) {
                if (data.dev_otp) userOTP = data.dev_otp;
                showMsg('msg2', 'New OTP sent!', false);
                document.querySelectorAll('.otp-inputs input').forEach(i => i.value = '');
                startCountdown(600);
            } else {
                showMsg('msg2', data.message, true);
            }
        }
    </script>
</body>
</html>
