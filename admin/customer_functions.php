<?php
date_default_timezone_set('Africa/Accra');
include('../includes/config.php');

function updateCustomerRecords($id, $firstname, $lastname, $middlename, $contact, $address, $email, $password) {
    global $conn;

    if (empty($firstname) || empty($lastname) || empty($contact) || empty($address) || empty($email)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
        echo json_encode($response);
        exit;
    }

    if(empty($password)) {
        $stmt = mysqli_prepare($conn, "UPDATE customers SET firstname=?, lastname=?, middlename=?, contact=?, address=?, email=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'ssssssi', $firstname, $lastname, $middlename, $contact, $address, $email, $id);
    }
    else {
        $password_param = md5($password);
        $stmt = mysqli_prepare($conn, "UPDATE customers SET firstname=?, lastname=?, middlename=?, contact=?, address=?, email=?, password=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'sssssssi', $firstname, $lastname, $middlename, $contact, $address, $email, $password_param, $id);
    }
    
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Customer member updated successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update Customer member');
        echo json_encode($response);
        exit;
    }
}

function addCustomerRecord($firstname, $lastname, $middlename, $contact, $address, $email, $password) {
    global $conn;

    if (empty($firstname) || empty($lastname) || empty($contact) || empty($address) || empty($email) || empty($password)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
        echo json_encode($response);
        exit;
    }

    // Check if the record already exists
    $stmt = mysqli_prepare($conn, "SELECT id FROM customers WHERE email=?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $num_rows = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($num_rows > 0) {
        $response = array('status' => 'error', 'message' => 'Customer member with this email already exists');
        echo json_encode($response);
        exit;
    }

    $password_param = md5($password);
    $stmt = mysqli_prepare($conn, "INSERT INTO customers (firstname, lastname, middlename, contact, address, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sssssss', $firstname, $lastname, $middlename, $contact, $address, $email, $password_param);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Customer member added successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to add Customer member');
        echo json_encode($response);
        exit;
    }
}

function deleteCustomer($id) {
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM customers WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Customer Member Deleted Successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete Customer');
        echo json_encode($response);
        exit;
    }
}


if(isset($_POST['action'])) {
    // Determine which action to perform
    if ($_POST['action'] === 'update-customer') {
        $id = $_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $response = updateCustomerRecords($id, $firstname, $lastname, $middlename, $contact, $address, $email, $password);
        echo $response;

    } elseif ($_POST['action'] === 'customer-add') {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $response = addCustomerRecord($firstname, $lastname, $middlename, $contact, $address, $email, $password);
        echo $response;

    } elseif ($_POST['action'] === 'delete-customer') {
        $id = $_POST['id'];
        $response = deleteCustomer($id);
        echo $response;
    }
}
?>