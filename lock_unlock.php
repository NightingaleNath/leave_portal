<?php
date_default_timezone_set('Africa/Accra');
session_start();
include('includes/config.php');

function lockUnlockScreen($lockUnlock, $enteredEmail) {
    global $conn;

    $email = $_SESSION['semail'];
    if (empty($lockUnlock) || empty($enteredEmail)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all fields');
        return json_encode($response);
    }

    if ($enteredEmail !== $email) {
        $response = array('status' => 'error', 'message' => 'Email does not match');
        return json_encode($response);
    }

    // Update the lock_unlock field in the appropriate table based on session role
    $tableName = ($_SESSION['srole'] == 1 || $_SESSION['srole'] == 2) ? 'staff' : 'customers';
    $newLockUnlock = $lockUnlock ? "false" : "true";

    $stmt = mysqli_prepare($conn, "UPDATE $tableName SET lock_unlock=? WHERE email=?");
    mysqli_stmt_bind_param($stmt, 'ss', $newLockUnlock, $email);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Your Screen Updated Successfully');
        return json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update Lock/Unlock Screen');
        return json_encode($response);
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'lock-unlock') {
        $lockUnlock = $_POST['lockUnlock'];
        $enteredEmail = $_POST['enteredEmail'];

        $response = lockUnlockScreen($lockUnlock, $enteredEmail);
        echo $response;
    } elseif ($_POST['action'] === 'save') {
        // Handle the save action if needed
    } else {
        $response = array('status' => 'error', 'message' => 'Invalid action');
        echo json_encode($response);
    }
} else {
    $response = array('status' => 'error', 'message' => 'No action specified');
    echo json_encode($response);
}
?>
