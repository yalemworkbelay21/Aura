<?php
// SMTP Configuration for Real Emails
// IMPORTANT: DO NOT share this file publicly if it contains your real password!

// 1. Go to your Google Account -> Security
// 2. Enable 2-Step Verification
// 3. Search for "App Passwords" in your Google Account search bar
// 4. Create an App Password for "Mail" / "Windows Computer"
// 5. Copy the 16-character password and paste it below (without spaces)

define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587); // Usually 587 for TLS, or 465 for SSL
define('SMTP_USER', getenv('SMTP_USER') ?: 'youremail@gmail.com'); // Replace with your FULL Gmail address
define('SMTP_PASS', getenv('SMTP_PASS') ?: 'your_16_digit_app_password'); // Replace with your generated App Password
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Aura Restaurant Admin');
?>
