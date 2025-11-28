<?php
require_once 'config.php';
require_once 'helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJSON(['message' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['username']) || !isset($input['password'])) {
    sendJSON(['message' => 'Invalid input'], 400);
}

$username = trim($input['username']);
$password = $input['password'];

if (empty($username) || empty($password)) {
    sendJSON(['message' => 'All fields are required'], 400);
}

// Get user
$stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    sendJSON(['message' => 'Invalid credentials'], 401);
}

// Generate JWT
$payload = [
    'user_id' => $user['id'],
    'username' => $user['username'],
    'exp' => time() + 3600 // 1 hour
];
$token = generateJWT($payload);

sendJSON(['message' => 'Login successful', 'token' => $token], 200);
?>