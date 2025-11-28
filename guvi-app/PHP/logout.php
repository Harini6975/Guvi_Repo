<?php
require_once 'config.php';
require_once 'helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJSON(['message' => 'Method not allowed'], 405);
}

$user = getUserFromToken();
$headers = getallheaders();
$authHeader = $headers['Authorization'];
preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches);
$token = $matches[1];

// Blacklist token until expiration
$payload = validateJWT($token);
if ($payload) {
    $exp = $payload['exp'];
    $ttl = $exp - time();
    if ($ttl > 0) {
        global $redis;
        $redis->setex('blacklist:' . $token, $ttl, '1');
    }
}

sendJSON(['message' => 'Logged out successfully']);
?>