<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Admin - Login</title>
    <link rel="stylesheet" href="./assets/css/style.css">
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
        .auth-card { background: rgba(22, 23, 24, 0.85); backdrop-filter: blur(12px); padding: 40px; border-radius: 16px; border: 1px solid rgba(228, 197, 144, 0.3); width: 400px; animation: fadeUp 0.8s ease-out; box-shadow: 0 20px 50px rgba(0,0,0,0.5); position: relative; z-index: 1; }
        .auth-card::before { content: ''; position: absolute; top: -1px; left: -1px; right: -1px; bottom: -1px; border-radius: 16px; background: linear-gradient(45deg, transparent, rgba(228, 197, 144, 0.1), transparent); z-index: -1; }
        .auth-logo { font-family: 'Forum', serif; color: #E4C590; font-size: 36px; text-align: center; margin-bottom: 30px; letter-spacing: 4px; text-transform: uppercase; animation: fadeUp 1s ease-out; }
        .input-group { margin-bottom: 25px; transition: 0.3s; }
        .auth-input { width: 100%; padding: 14px; background: #1a1a1a; border: 1px solid #333; color: white; border-radius: 8px; outline: none; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .auth-input:focus { border-color: #E4C590; background: #222; box-shadow: 0 0 15px rgba(228, 197, 144, 0.2); transform: scale(1.02); }
        .auth-btn { width: 100%; padding: 14px; background: #E4C590; color: #0a0b0c; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 16px; letter-spacing: 1px; transition: all 0.4s; animation: fadeUp 1.2s ease-out; }
        .auth-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(228, 197, 144, 0.4); background: #f0d5a8; }
        .auth-links { margin-top: 25px; text-align: center; font-size: 14px; color: #a9a9a9; animation: fadeUp 1.4s ease-out; }
        .auth-links a { color: #E4C590; text-decoration: none; margin: 0 12px; transition: 0.3s; position: relative; }
        .auth-links a::after { content: ''; position: absolute; width: 0; height: 1px; bottom: -2px; left: 0; background: #E4C590; transition: 0.3s; }
        .auth-links a:hover { color: white; }
        .auth-links a:hover::after { width: 100%; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">AURA ADMIN</div>
        <form onsubmit="handleLogin(event)">
            <div class="input-group"><input type="email" id="email" class="auth-input" placeholder="Email Address" required></div>
            <div class="input-group"><input type="password" id="password" class="auth-input" placeholder="Password" required></div>
            <button type="submit" class="auth-btn">LOGIN</button>
        </form>
        <div class="auth-links">
            <a href="signup.php">Signup</a> | <a href="forgot.php">Forgot Password?</a>
        </div>
    </div>
    <script>
        async function handleLogin(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const response = await fetch('auth_api.php?action=login', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ email, password })
            });
            const data = await response.json();
            if (data.success) { window.location.href = 'admin.php'; }
            else { alert(data.message); }
        }
    </script>
</body>
</html>
