<?php
require_once 'config.php';

// Simple JWT implementation (for demo; use a library in production)
function base64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

function base64UrlDecode($data) {
    return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
}

function generateJWT($payload) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode($payload);
    $headerEncoded = base64UrlEncode($header);
    $payloadEncoded = base64UrlEncode($payload);
    $signature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, JWT_SECRET, true);
    $signatureEncoded = base64UrlEncode($signature);
    return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
}

function validateJWT($jwt) {
    global $redis;
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;

    $header = base64UrlDecode($parts[0]);
    $payload = base64UrlDecode($parts[1]);
    $signature = $parts[2];

    $expectedSignature = base64UrlEncode(hash_hmac('sha256', $parts[0] . "." . $parts[1], JWT_SECRET, true));

    if ($signature !== $expectedSignature) return false;

    $payloadData = json_decode($payload, true);
    if ($payloadData['exp'] < time()) return false;

    // Check if blacklisted
    if ($redis->get('blacklist:' . $jwt)) return false;

    return $payloadData;
}

function getUserFromToken() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['message' => 'Authorization header missing']);
        exit;
    }

    $authHeader = $headers['Authorization'];
    if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid authorization header']);
        exit;
    }

    $token = $matches[1];
    $payload = validateJWT($token);
    if (!$payload) {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid or expired token']);
        exit;
    }

    return $payload;
}

function sendJSON($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>