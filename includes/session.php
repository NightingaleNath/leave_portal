<?php
session_start();

// Check whether the session variable SESS_MEMBER_ID is present or not
if (!isset($_SESSION['slogin']) || (trim($_SESSION['slogin']) == '')) {
    // Redirect to the login page
    header("Location: ../index.php");
    exit;
}

// Check if the session has expired
$lastActivity = $_SESSION['last_activity'];
$sessionExpiration = 60 * 30; // Session expires after 5 minutes of inactivity

if (time() - $lastActivity > $sessionExpiration) {
    // Session has expired, destroy the session and redirect to the login page
    session_unset();
    session_destroy();
    
    echo "<script>alert('Your session has expired. Please log in again.');</script>";

    // Redirect to the login page
    echo "<script>window.location = '../index.php';</script>";
    exit;
}

// Update the last activity time
$_SESSION['last_activity'] = time();

$session_id = $_SESSION['slogin'];
$session_role = $_SESSION['srole'];
$session_semail = $_SESSION['semail'];
$session_sfirstname = $_SESSION['sfirstname'];
$session_slastname = $_SESSION['slastname'];
$session_smiddlename = $_SESSION['smiddlename'];
$session_scontact = $_SESSION['scontact'];
$session_sdesignation = $_SESSION['sdesignation'];
$session_sstaff_id = $_SESSION['sstaff_id'];
$session_image = $_SESSION['simageurl'];
$session_depart = $_SESSION['department'];
$session_supervisor = $_SESSION['is_supervisor'];
?>
