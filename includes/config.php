<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'leave_portal');

// Establish database connection using MySQLi
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    die(json_encode(array('status' => 'error', 'message' => 'Database connection failed')));
}

// Optionally, you can keep the PDO connection for other parts of the application
try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} catch (PDOException $e) {
    error_log("PDO Connection Error: " . $e->getMessage());
    exit(json_encode(array('status' => 'error', 'message' => 'Database connection failed')));
}
?>
