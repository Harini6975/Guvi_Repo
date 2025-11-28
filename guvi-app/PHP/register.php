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
if (!$input || !isset($input['username']) || !isset($input['email']) || !isset($input['password'])) {
    sendJSON(['message' => 'Invalid input'], 400);
}

$username = trim($input['username']);
$email = trim($input['email']);
$password = $input['password'];

if (empty($username) || empty($email) || empty($password)) {
    sendJSON(['message' => 'All fields are required'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJSON(['message' => 'Invalid email format'], 400);
}

// Check if username exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
    sendJSON(['message' => 'Username already exists'], 409);
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
if ($stmt->execute([$username, $email, $hashedPassword])) {
    sendJSON(['message' => 'User registered successfully'], 201);
} else {
    sendJSON(['message' => 'Registration failed'], 500);
}
?>