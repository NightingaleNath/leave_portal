<?php 
date_default_timezone_set('Africa/Accra');
include('../includes/config.php');

function updateLeaveType($id, $dname, $description, $status, $assigned) {
    global $conn;

    if (empty($dname) || empty($description) || empty($status) || empty($assigned)) {
    $response = array('status' => 'error', 'message' => 'Please fill in all fields');
    echo json_encode($response);
    exit;
    }

    $stmt = mysqli_prepare($conn, "UPDATE tblleavetype SET leave_type=?, description=?, assign_days=?, status=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'ssiii', $dname, $description, $assigned, $status, $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Leave Type Updated Successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update leave type');
        echo json_encode($response);
        exit;
    }
}

function saveLeaveType($dname, $description, $status, $assigned) {
    global $conn;

    if (empty($dname) || empty($description) || empty($status) || empty($assigned)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all fields');
        echo json_encode($response);
        exit;
    }

    // Check if the department already exists
    $stmt = mysqli_prepare($conn, "SELECT * FROM tblleavetype WHERE leave_type = ?");
    mysqli_stmt_bind_param($stmt, "s", $dname);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_num_rows($result);

    if ($count > 0) { 
        $response = array('status' => 'error', 'message' => 'Leave Type already exists');
        echo json_encode($response);
        exit;
    } else {
        // Prepare the query to insert a new department with creation_date
        $currentDateTime = date('Y-m-d H:i:s'); // Get the current date and time
        $stmt = mysqli_prepare($conn, "INSERT INTO tblleavetype (leave_type, description, creation_date, assign_days, status) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssii", $dname, $description, $currentDateTime, $assigned, $status);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $response = array('status' => 'success', 'message' => 'Leave Type added successfully');
            echo json_encode($response);
            exit;
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to add leave type');
            echo json_encode($response);
            exit;
        }
    }
}

function deleteLeaveType($id) {
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM tblleavetype WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Leave Type Deleted Successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete leave type');
        echo json_encode($response);
        exit;
    }
}


if(isset($_POST['action'])) {
    // Determine which action to perform
    if ($_POST['action'] === 'update') {
        $dname = $_POST['dname'];
        $description = $_POST['description'];
        $id = $_POST['id'];
        $assigned = $_POST['assigned'];
        $status = $_POST['status'];
        $response = updateLeaveType($id, $dname, $description, $status, $assigned);
        echo $response;
    } elseif ($_POST['action'] === 'save') {
        $dname = $_POST['dname'];
        $description = $_POST['description'];
        $assigned = $_POST['assigned'];
        $status = $_POST['status'];
        $response = saveLeaveType($dname, $description, $status, $assigned);
        echo $response;
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $response = deleteLeaveType($id);
        echo $response;
    }
}
?>
