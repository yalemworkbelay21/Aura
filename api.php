<?php
header('Content-Type: application/json');
require_once 'db_config.php';
$action = $_GET['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($action === 'save_res') {
        $stmt = $pdo->prepare("INSERT INTO reservations (name, phone, person, date, time) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $input['name'],
            $input['phone'],
            $input['person'],
            $input['date'],
            $input['time']
        ]);
        echo json_encode(['success' => true]);
    }
    if ($action === 'save_chat') {
        $msg = $input['msg'];
        $isAdmin = isset($input['is_admin']) ? $input['is_admin'] : 0;
        $stmt = $pdo->prepare("INSERT INTO chats (msg, time, is_admin) VALUES (?, ?, ?)");
        $stmt->execute([$msg, $input['time'], $isAdmin]);

        // Auto-reply for customers (only if msg is from customer)
        if (!$isAdmin) {
            $reply = "Thank you for contacting Aura. A manager will respond soon!";
            
            // Basic keyword detection for auto-reply
            $lowerMsg = strtolower($msg);
            if (strpos($lowerMsg, 'menu') !== false) {
                $reply = "You can view our full menu at https://aura-restaurant.com/menu";
            } elseif (strpos($lowerMsg, 'book') !== false || strpos($lowerMsg, 'reservation') !== false) {
                $reply = "To book a table, please visit our Reservation section or call us at +251 977 24 9999.";
            } elseif (strpos($lowerMsg, 'location') !== false || strpos($lowerMsg, 'where') !== false) {
                $reply = "We are located at Aura, Rakan Building, Cape Verde St, Addis Ababa, Ethiopia.";
            }

            $stmt = $pdo->prepare("INSERT INTO chats (msg, time, is_admin) VALUES (?, ?, 1)");
            $stmt->execute([$reply, date('m/d/Y, h:i:s A'), 1]);
        }
        
        echo json_encode(['success' => true]);
    }
    if ($action === 'update_res') {
        $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        $stmt->execute([$input['status'], $input['id']]);
        echo json_encode(['success' => true]);
    }
    if ($action === 'delete_res') {
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(['success' => true]);
    }
    if ($action === 'save_subscriber') {
        $stmt = $pdo->prepare("INSERT IGNORE INTO subscribers (email) VALUES (?)");
        $stmt->execute([$input['email']]);
        echo json_encode(['success' => true]);
    }
    if ($action === 'delete_subscriber') {
        $stmt = $pdo->prepare("DELETE FROM subscribers WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(['success' => true]);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'get_all') {
        $resStmt = $pdo->query("SELECT * FROM reservations ORDER BY created_at DESC");
        $chatStmt = $pdo->query("SELECT * FROM chats ORDER BY created_at ASC");
        $subStmt = $pdo->query("SELECT * FROM subscribers ORDER BY created_at DESC");

        echo json_encode([
            'reservations' => $resStmt->fetchAll(),
            'chats' => $chatStmt->fetchAll(),
            'subscribers' => $subStmt->fetchAll()
        ]);
    }
}
?>