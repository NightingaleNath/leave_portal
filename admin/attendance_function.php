<?php 
date_default_timezone_set('Africa/Accra');
session_start();
include('../includes/config.php');

function clockIn($staff_id) {
    global $conn;

    error_log("Clock in attempt for staff_id: " . $staff_id);
    
    // Debug session
    error_log("Session staff_id: " . (isset($_SESSION['sstaff_id']) ? $_SESSION['sstaff_id'] : 'not set'));

    if ($staff_id !== $_SESSION['sstaff_id']) {
        $response = array('status' => 'error', 'message' => 'Staff ID does not match session ID');
        echo json_encode($response);
        exit;
    }

    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');

     // Check if staff_id exists in tblemployees
    $stmt = mysqli_prepare($conn, "SELECT * FROM tblemployees WHERE staff_id = ?");
    if (!$stmt) {
        $response = array('status' => 'error', 'message' => 'Query preparation failed: ' . mysqli_error($conn));
        echo json_encode($response);
        exit;
    }
    mysqli_stmt_bind_param($stmt, 's', $staff_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        $response = array('status' => 'error', 'message' => 'Invalid Staff ID');
        echo json_encode($response);
        exit;
    }

    // Check if already clocked in today
    $stmt = mysqli_prepare($conn, "SELECT * FROM tblattendance WHERE staff_id = ? AND DATE(date) = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $staff_id, $currentDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $response = array('status' => 'error', 'message' => 'You have already clocked in today.');
        echo json_encode($response);
        exit;
    }

    // Insert clock in time
    $stmt = mysqli_prepare($conn, "INSERT INTO tblattendance (staff_id, time_in, date) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $staff_id, $currentTime, $currentDate);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Clocked in successfully.');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to clock in.');
        echo json_encode($response);
        exit;
    }
}

function clockOut($staff_id) {
    global $conn;

    error_log("Clock in attempt for staff_id: " . $staff_id);
    
    // Debug session
    error_log("Session staff_id: " . (isset($_SESSION['sstaff_id']) ? $_SESSION['sstaff_id'] : 'not set'));

    if ($staff_id !== $_SESSION['sstaff_id']) {
        $response = array('status' => 'error', 'message' => 'Staff ID does not match session ID');
        echo json_encode($response);
        exit;
    }

    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');

     // Check if staff_id exists in tblemployees
    $stmt = mysqli_prepare($conn, "SELECT * FROM tblemployees WHERE staff_id = ?");
    if (!$stmt) {
        $response = array('status' => 'error', 'message' => 'Query preparation failed: ' . mysqli_error($conn));
        echo json_encode($response);
        exit;
    }
    mysqli_stmt_bind_param($stmt, 's', $staff_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        $response = array('status' => 'error', 'message' => 'Invalid Staff ID');
        echo json_encode($response);
        exit;
    }

    // Check if clocked in today
    $stmt = mysqli_prepare($conn, "SELECT * FROM tblattendance WHERE staff_id = ? AND DATE(date) = ? AND time_out IS NULL");
    mysqli_stmt_bind_param($stmt, 'ss', $staff_id, $currentDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        $response = array('status' => 'error', 'message' => 'You must clock in before clocking out.');
        echo json_encode($response);
        exit;
    }

    // Update clock out time
    $stmt = mysqli_prepare($conn, "UPDATE tblattendance SET time_out = ? WHERE staff_id = ? AND DATE(date) = ? AND time_out IS NULL");
    mysqli_stmt_bind_param($stmt, 'sss', $currentTime, $staff_id, $currentDate);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Clocked out successfully.');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to clock out.');
        echo json_encode($response);
        exit;
    }
}

function deleteAttendance($attendanceId) {
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM tblattendance WHERE attendance_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $attendanceId);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Attendance record deleted successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete attendance record');
    }
    echo json_encode($response);
    exit;
}

if(isset($_POST['action'])) {
    if ($_POST['action'] === 'clock_in') {
        $staff_id = $_POST['staff_id'];
        clockIn($staff_id);

    } elseif ($_POST['action'] === 'clock_out') {
        $staff_id = $_POST['staff_id'];
        clockOut($staff_id);

    } elseif ($_POST['action'] === 'delete_attendance') {
        $attendanceId = $_POST['attendance_id'];
        deleteAttendance($attendanceId);
    }
}
?>