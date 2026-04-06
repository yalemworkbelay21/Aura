<?php
session_start();
header('Content-Type: application/json');
require_once 'db_config.php';

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

// ============ SIGNUP ============
if ($action === 'signup') {
    $username = trim($input['username'] ?? '');
    $email    = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    // Validate inputs
    if (empty($username) || strlen($username) < 2) {
        echo json_encode(['success' => false, 'message' => 'Name must be at least 2 characters.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
        exit;
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $hashedPassword]);
        echo json_encode(['success' => true, 'message' => 'Account created successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Signup failed: ' . $e->getMessage()]);
    }
    exit;
}

// ============ LOGIN ============
if ($action === 'login') {
    $email    = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id']    = $user['id'];
        $_SESSION['username']    = $user['username'];
        $_SESSION['email']       = $user['email'];
        $_SESSION['profile_pic'] = $user['profile_pic'] ?: './assets/images/Mequ.jpg';
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
    exit;
}

// ============ FORGOT PASSWORD - SEND OTP ============
if ($action === 'forgot') {
    $email = trim($input['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'No account found with this email.']);
        exit;
    }

    // Generate 6-digit OTP
    $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    // Save OTP to database using MySQL NOW() to avoid timezone mismatch
    $stmt = $pdo->prepare("UPDATE admins SET otp_code = ?, otp_expiry = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE email = ?");
    $stmt->execute([$otp, $email]);

    // Send email with OTP via PHPMailer
    require 'smtp_config.php';
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USER, SMTP_FROM_NAME);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Aura Admin - Password Reset OTP';
        $mail->Body    = "
        <html>
        <head><title>Password Reset</title></head>
        <body style='font-family: Arial, sans-serif; background: #1a1a1a; color: #ffffff; padding: 40px;'>
            <div style='max-width: 500px; margin: 0 auto; background: #222; padding: 30px; border-radius: 12px; border: 1px solid #E4C590;'>
                <h2 style='color: #E4C590; text-align: center; font-size: 24px;'>AURA RESTAURANT</h2>
                <p style='text-align: center; color: #ccc;'>Your password reset OTP code is:</p>
                <div style='text-align: center; margin: 25px 0;'>
                    <span style='font-size: 36px; font-weight: bold; color: #E4C590; letter-spacing: 8px; background: #333; padding: 15px 30px; border-radius: 10px;'>$otp</span>
                </div>
                <p style='text-align: center; color: #999; font-size: 13px;'>This code expires in 10 minutes.</p>
                <p style='text-align: center; color: #999; font-size: 12px;'>If you didn't request this, please ignore this email.</p>
            </div>
        </body>
        </html>";

        $mail->send();
        echo json_encode([
            'success' => true, 
            'message' => 'Real OTP email sent to ' . $email, 
            'show_otp_form' => true
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => true, 
            'message' => 'SMTP inactive. (Local Test Mode)', 
            'show_otp_form' => true, 
            'dev_otp' => $otp 
        ]);
    }
    exit;
}

// ============ VERIFY OTP & RESET PASSWORD ============
if ($action === 'verify_otp') {
    $email       = trim($input['email'] ?? '');
    $otp         = trim($input['otp'] ?? '');
    $newPassword = $input['new_password'] ?? '';

    if (empty($otp) || strlen($otp) !== 6) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid 6-digit OTP.']);
        exit;
    }
    if (strlen($newPassword) < 6) {
        echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND otp_code = ? AND otp_expiry > NOW()");
    $stmt->execute([$email, $otp]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired OTP code.']);
        exit;
    }

    // Reset password and clear OTP
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE admins SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE email = ?");
    $stmt->execute([$hashedPassword, $email]);

    echo json_encode(['success' => true, 'message' => 'Password reset successful! Please login.']);
    exit;
}

// ============ UPDATE PROFILE ============
if ($action === 'update_profile') {
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit;
    }
    
    $username = trim($input['username'] ?? '');
    $email    = trim($input['email'] ?? '');
    $adminId  = $_SESSION['admin_id'];

    $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ? WHERE id = ?");
    $stmt->execute([$username, $email, $adminId]);
    
    $_SESSION['username'] = $username;
    $_SESSION['email']    = $email;
    
    echo json_encode(['success' => true]);
    exit;
}

// ============ CHANGE PASSWORD ============
if ($action === 'change_password') {
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit;
    }
    
    $adminId = $_SESSION['admin_id'];
    $newPass = password_hash($input['password'], PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
    $stmt->execute([$newPass, $adminId]);
    echo json_encode(['success' => true]);
    exit;
}

// ============ UPLOAD PROFILE PIC ============
if ($action === 'upload_profile_pic') {
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        exit;
    }
    
    if (isset($_FILES['profile_pic'])) {
        $file = $_FILES['profile_pic'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'profile_' . $_SESSION['admin_id'] . '.' . $ext;
        $targetDir = 'assets/images/';
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $stmt = $pdo->prepare("UPDATE admins SET profile_pic = ? WHERE id = ?");
            $stmt->execute([$targetFile, $_SESSION['admin_id']]);
            $_SESSION['profile_pic'] = $targetFile;
            echo json_encode(['success' => true, 'path' => $targetFile]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
    }
    exit;
}
?>
