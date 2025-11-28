<?php
// MySQL Configuration
define('MYSQL_HOST', 'localhost');
define('MYSQL_DB', 'guvi_db');
define('MYSQL_USER', 'root');
define('MYSQL_PASS', '');

// Redis Configuration
define('REDIS_HOST', 'localhost');
define('REDIS_PORT', 6379);

// MongoDB Configuration
define('MONGO_HOST', 'mongodb://localhost:27017');
define('MONGO_DB', 'guvi_mongo');

// JWT Secret
define('JWT_SECRET', 'your-secret-key-here'); // Change this in production

// MySQL PDO Connection
try {
    $pdo = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB, MYSQL_USER, MYSQL_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("MySQL Connection failed: " . $e->getMessage());
}

// Redis Connection
$redis = new Redis();
try {
    $redis->connect(REDIS_HOST, REDIS_PORT);
} catch (Exception $e) {
    die("Redis Connection failed: " . $e->getMessage());
}

// MongoDB Connection
require_once __DIR__ . '/../vendor/autoload.php'; // Assuming composer install
$mongoClient = new MongoDB\Client(MONGO_HOST);
$mongoDB = $mongoClient->selectDatabase(MONGO_DB);
?>