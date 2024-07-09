<?php
session_start();
include('includes/config.php');
require_once 'alerts.php';

function login($email, $password) {
    global $conn;

    $password = md5($password); // Hash the password

    // Query staff table
    $staffQuery = mysqli_query($conn, "SELECT * FROM tblemployees WHERE email_id='$email' AND password='$password'");
    if (!$staffQuery) {
        return array('status' => 'error', 'message' => "Error: " . mysqli_error($conn));
    } else {
        $staffCount = mysqli_num_rows($staffQuery);
        if ($staffCount > 0) {
            $recordsRow = mysqli_fetch_assoc($staffQuery);
            return checkAndSetSession($recordsRow);
        } else {
            // Query customers table
            return array('status' => 'error', 'message' => 'Invalid Details');
        }
    }
}

function checkAndSetSession($userRecord) {
    if ($userRecord['lock_unlock'] == "true") {
        return array('status' => 'error', 'message' => 'Your account is locked. Please unlock it using your email or contact an admin for assistance.');
    }

    // Set session variables and redirect based on user type
    $_SESSION['slogin'] = $userRecord['emp_id'];
    $_SESSION['srole'] = $userRecord['role'];
    $_SESSION['semail'] = $userRecord['email_id'];
    $_SESSION['sstaff_id'] = $userRecord['staff_id'];
    $_SESSION['sfirstname'] = $userRecord['first_name'];
    $_SESSION['slastname'] = $userRecord['last_name'];
    $_SESSION['smiddlename'] = $userRecord['middle_name'];
    $_SESSION['scontact'] = $userRecord['phone_number'];
    $_SESSION['sdesignation'] = $userRecord['designation'];
    $_SESSION['is_supervisor'] = $userRecord['is_supervisor'];
    $_SESSION['simageurl'] = $userRecord['image_path'];
    $_SESSION['last_activity'] = time(); // Set the last activity time

    if ($userRecord['role'] == 'Admin') {
        $_SESSION['department'] = $userRecord['department'];
        $userType = 'admin';
        return array('status' => 'success', 'message' => 'Successfully logged in as admin', 'role' => $userType);
    } elseif ($userRecord['role'] == 'Manager') {
        $_SESSION['department'] = $userRecord['department'];
        $userType = 'manager';
        return array('status' => 'success', 'message' => 'Successfully logged in as manager', 'role' => $userType);
    } elseif ($userRecord['role'] == 'Staff') {
        $_SESSION['department'] = $userRecord['department'];
        $userType = 'staff';
        return array('status' => 'success', 'message' => 'Successfully logged in as staff', 'role' => $userType);
    } else {
        return array('status' => 'error', 'message' => 'Invalid user type');
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'save') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $loginResponse = login($email, $password);

        header('Content-Type: application/json'); // Set the content type to JSON
        echo json_encode($loginResponse);
        exit;
    }
}
?>