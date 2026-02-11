
<?php
// Database credentials
$host = 'localhost';
$db   = 'dosecare';
$user = 'root'; // Default XAMPP/WAMP user
$pass = '';     // Default XAMPP/WAMP password (usually empty)
$charset = 'utf8mb4';


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
    //   echo "Connected successfully to DoseCare"; 
} catch (\PDOException $e) {
    
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}



// Function to record system logs (FR23)
function logSystemActivity($pdo, $user_type, $user_id, $action, $details) {
    try {
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $pdo->prepare("INSERT INTO system_logs (user_type, user_id, action, details, ip_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_type, $user_id, $action, $details, $ip]);
    } catch (PDOException $e) {
        // Silently fail if logging fails
    }
}
?>