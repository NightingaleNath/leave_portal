<?php
session_start();

// Destroy all session variables
session_unset();
session_destroy();

// Redirect to the login page
header("Location: index.php");
exit;
?>
