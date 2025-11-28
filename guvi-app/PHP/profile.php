<?php
require_once 'config.php';
require_once 'helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$user = getUserFromToken();
$userId = $user['user_id'];

$collection = $mongoDB->profiles;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $profile = $collection->findOne(['user_id' => $userId]);
    if ($profile) {
        sendJSON([
            'username' => $user['username'],
            'email' => $profile['email'],
            'bio' => $profile['bio'] ?? ''
        ]);
    } else {
        // Return default
        sendJSON([
            'username' => $user['username'],
            'email' => '',
            'bio' => ''
        ]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['email'])) {
        sendJSON(['message' => 'Invalid input'], 400);
    }

    $email = trim($input['email']);
    $bio = trim($input['bio'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJSON(['message' => 'Invalid email format'], 400);
    }

    $updateData = [
        'email' => $email,
        'bio' => $bio,
        'updated_at' => new MongoDB\BSON\UTCDateTime()
    ];

    $result = $collection->updateOne(
        ['user_id' => $userId],
        ['$set' => $updateData],
        ['upsert' => true]
    );

    if ($result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0) {
        sendJSON(['message' => 'Profile updated successfully']);
    } else {
        sendJSON(['message' => 'No changes made'], 200);
    }
} else {
    sendJSON(['message' => 'Method not allowed'], 405);
}
?>