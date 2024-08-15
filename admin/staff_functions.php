<?php
date_default_timezone_set('Africa/Accra');
session_start();
include('../includes/config.php');

function resizeImage($sourcePath, $destinationPath, $width, $height) {
     if (!function_exists('imagecreatefromjpeg') || !function_exists('imagejpeg')) {
        throw new Exception('GD library is not available');
    }
    
    list($originalWidth, $originalHeight) = getimagesize($sourcePath);
    $src = imagecreatefromjpeg($sourcePath);
    $dst = imagecreatetruecolor($width, $height);
    
    // Resize
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
    
    // Save the resized image
    imagejpeg($dst, $destinationPath);
    
    // Free memory
    imagedestroy($src);
    imagedestroy($dst);
}

function updateStaffRecords($edit_id, $firstname, $lastname, $middlename, $contact, $designation, $department, $email, $password, $gender, $is_supervisor, $role, $staff_id, $image_path) {
    global $conn;

    if (empty($department) || empty($firstname) || empty($lastname) || empty($contact) || empty($designation) || empty($email)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
        echo json_encode($response);
        exit;
    }

    // Check if the image file is provided
    if ($image_path !== null && isset($image_path['name']) && !empty($image_path['name'])) {
        // Upload the image
        $image_upload_dir = '../uploads/images/';
        $image_name = $staff_id . '_' . basename($image_path['name']);
        $image_target_path = $image_upload_dir . $image_name;

        if (!move_uploaded_file($image_path['tmp_name'], $image_target_path)) {
            $response = array('status' => 'error', 'message' => 'Failed to upload the image');
            echo json_encode($response);
            exit;
        }

         // Resize the image to 230x230
        resizeImage($image_target_path, $image_target_path, 230, 230);

        // If a new image is provided, remove the old image from the storage folder
        $old_image_path = ''; // Get the old image path from the database
        $stmt = mysqli_prepare($conn, "SELECT image_path FROM tblemployees WHERE emp_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $edit_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $old_image_path);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if (!empty($old_image_path) && file_exists($old_image_path)) {
            unlink($old_image_path); // Delete the old image
        }

    } else {
        $image_target_path = ''; // Empty image path
    }

    // Check if the password is empty
    if (empty($password)) {
        $password_param = ''; // Empty password
    } else {
        $password_param = md5($password);
    }

    // Construct the SQL query based on the presence of image and password
    if (empty($image_target_path) && empty($password_param)) {
        // Both image and password are empty
        $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET department=?, first_name=?, last_name=?, middle_name=?, phone_number=?, designation=?, email_id=?, gender=?, role=?, staff_id=?, is_supervisor=? WHERE emp_id=?");
        mysqli_stmt_bind_param($stmt, 'isssssssssii', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $gender, $role, $staff_id, $is_supervisor, $edit_id);
    } elseif (empty($image_target_path)) {
        // Only image is empty
        $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET department=?, first_name=?, last_name=?, middle_name=?, phone_number=?, designation=?, email_id=?, password=?, gender=?, role=?, staff_id=?, is_supervisor=? WHERE emp_id=?");
        mysqli_stmt_bind_param($stmt, 'issssssssssii', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $password_param, $gender, $role, $staff_id, $is_supervisor, $edit_id);
    } elseif (empty($password_param)) {
        // Only password is empty
        $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET department=?, first_name=?, last_name=?, middle_name=?, phone_number=?, designation=?, email_id=?, gender=?, role=?, image_path=?, staff_id=?, is_supervisor=? WHERE emp_id=?");
        mysqli_stmt_bind_param($stmt, 'issssssssssii', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $gender, $role, $image_target_path, $staff_id, $is_supervisor, $edit_id);
    } else {
        // Both image and password are provided
        $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET department=?, first_name=?, last_name=?, middle_name=?, phone_number=?, designation=?, email_id=?, password=?, gender=?, role=?, image_path=?, staff_id=?, is_supervisor=? WHERE emp_id=?");
        mysqli_stmt_bind_param($stmt, 'isssssssssssii', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $password_param, $gender, $role, $image_target_path, $staff_id, $is_supervisor, $edit_id);
    }

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Staff member updated successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update staff member');
        echo json_encode($response);
        exit;
    }
}

function addStaffRecord($firstname, $lastname, $middlename, $contact, $designation, $department, $email, $password, $role, $is_supervisor, $staff_id, $gender, $image_path) {
    global $conn;

    if (empty($department) || empty($firstname) || empty($lastname) || empty($contact) ||
        empty($designation) || empty($email) || empty($password) || empty($role) || empty($image_path)) {
        $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
        echo json_encode($response);
        exit;
    }

    // Check if the record already exists
    $stmt = mysqli_prepare($conn, "SELECT emp_id FROM tblemployees WHERE email_id=?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $num_rows = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($num_rows > 0) {
        $response = array('status' => 'error', 'message' => 'Staff member with this email already exists');
        echo json_encode($response);
        exit;
    }

    // Upload the image
    $image_upload_dir = '../uploads/images/';
    $image_name = $staff_id . '_' . basename($image_path['name']);
    $image_target_path = $image_upload_dir . $image_name;

    if (!move_uploaded_file($image_path['tmp_name'], $image_target_path)) {
        $response = array('status' => 'error', 'message' => 'Failed to upload the image');
        echo json_encode($response);
        exit;
    }

     // Resize the image to 230x230
    resizeImage($image_target_path, $image_target_path, 230, 230);

    // Insert the record into the database
    $password_param = md5($password);
    $stmt = mysqli_prepare($conn, "INSERT INTO tblemployees (department, first_name, last_name, middle_name, phone_number, designation, email_id, password, gender, image_path, role, staff_id, is_supervisor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'isssssssssssi', $department, $firstname, $lastname, $middlename, $contact, $designation, $email, $password_param, $gender, $image_target_path, $role, $staff_id, $is_supervisor);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Staff member added successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to add staff member');
        echo json_encode($response);
        exit;
    }
}

function deleteStaff($id) {
    global $conn;

    // Get the old image path before deleting the staff member
    $old_image_path = ''; // Initialize the old image path
    $stmt = mysqli_prepare($conn, "SELECT image_path FROM tblemployees WHERE emp_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $old_image_path);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Delete the staff member
    $stmt = mysqli_prepare($conn, "DELETE FROM tblemployees WHERE emp_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // If the staff member is deleted successfully, check and delete the associated image
        if (!empty($old_image_path) && file_exists($old_image_path)) {
            unlink($old_image_path); // Delete the old image
        }

        $response = array('status' => 'success', 'message' => 'Staff Member Deleted Successfully');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to delete staff');
        echo json_encode($response);
        exit;
    }
}

function assignLeaveTypes($employeeId, $leaveTypes) {
    global $conn;

    if (empty($employeeId) || empty($leaveTypes) || !is_array($leaveTypes)) {
        $response = array('status' => 'error', 'message' => 'Please provide valid employee ID and leave types');
        echo json_encode($response);
        exit;
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Fetch existing leave types for the employee
        $existingLeaveTypesQuery = "SELECT leave_type_id, available_days FROM employee_leave_types WHERE emp_id = ?";
        $stmt = mysqli_prepare($conn, $existingLeaveTypesQuery);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, 'i', $employeeId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
        }
        $result = mysqli_stmt_get_result($stmt);
        $existingLeaveTypes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $existingLeaveTypes[$row['leave_type_id']] = $row['available_days'];
        }
        mysqli_stmt_close($stmt);

        // Fetch all leave types and their assigned days
        $leaveTypesQuery = "SELECT id, assign_days FROM tblleavetype";
        $leaveTypesResult = mysqli_query($conn, $leaveTypesQuery);
        if (!$leaveTypesResult) {
            throw new Exception('Query failed: ' . mysqli_error($conn));
        }
        $leaveTypesAssignDays = [];
        while ($row = mysqli_fetch_assoc($leaveTypesResult)) {
            $leaveTypesAssignDays[$row['id']] = $row['assign_days'];
        }

        // Check which leave types should be added, updated, or deleted
        foreach ($existingLeaveTypes as $leaveTypeId => $availableDays) {
            if (!in_array($leaveTypeId, $leaveTypes)) {
                // If leave type is not in the new assignment and has not been used, delete it
                if ($availableDays == $leaveTypesAssignDays[$leaveTypeId]) {
                    $stmt = mysqli_prepare($conn, "DELETE FROM employee_leave_types WHERE emp_id = ? AND leave_type_id = ?");
                    if (!$stmt) {
                        throw new Exception('Prepare failed: ' . mysqli_error($conn));
                    }
                    mysqli_stmt_bind_param($stmt, 'ii', $employeeId, $leaveTypeId);
                    if (!mysqli_stmt_execute($stmt)) {
                        throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }

        // Insert or update new leave types
        foreach ($leaveTypes as $leaveTypeId) {
            if (array_key_exists($leaveTypeId, $existingLeaveTypes)) {
                // Check if the leave type has been used
                if ($existingLeaveTypes[$leaveTypeId] == $leaveTypesAssignDays[$leaveTypeId]) {
                    // Leave type has not been used, update available days
                    $stmt = mysqli_prepare($conn, "UPDATE employee_leave_types SET available_days = ? WHERE emp_id = ? AND leave_type_id = ?");
                    if (!$stmt) {
                        throw new Exception('Prepare failed: ' . mysqli_error($conn));
                    }
                    mysqli_stmt_bind_param($stmt, 'iii', $leaveTypesAssignDays[$leaveTypeId], $employeeId, $leaveTypeId);
                    if (!mysqli_stmt_execute($stmt)) {
                        throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                // Insert new leave types
                $assign_days = $leaveTypesAssignDays[$leaveTypeId];
                $stmt = mysqli_prepare($conn, "INSERT INTO employee_leave_types (emp_id, leave_type_id, available_days) VALUES (?, ?, ?)");
                if (!$stmt) {
                    throw new Exception('Prepare failed: ' . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt, 'iii', $employeeId, $leaveTypeId, $assign_days);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
            }
        }

        // Commit transaction
        mysqli_commit($conn);

        $response = array('status' => 'success', 'message' => 'Leave types assigned successfully');
        echo json_encode($response);
        exit;

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);

        $response = array('status' => 'error', 'message' => 'Failed to assign leave types: ' . $e->getMessage());
        echo json_encode($response);
        exit;
    }
}

function assignSupervisor($employeeId, $supervisorId) {
    global $conn;

    // Check for empty inputs
    if (empty($employeeId) || empty($supervisorId)) {
        $response = array('status' => 'error', 'message' => 'Please provide both employee ID and supervisor ID');
        return json_encode($response);
    }

    // Prepare the update statement
    $stmt = mysqli_prepare($conn, "UPDATE tblemployees SET supervisor_id = ? WHERE emp_id = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $supervisorId, $employeeId);
    $result = mysqli_stmt_execute($stmt);

    // Check the result and return appropriate response
    if ($result) {
        $response = array('status' => 'success', 'message' => 'Supervisor assigned successfully');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to assign supervisor');
    }

    return json_encode($response);
}

if(isset($_POST['action'])) {
    // Determine which action to perform
    if ($_POST['action'] === 'updateStaff') {
        $edit_id = $_POST['edit_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $contact = $_POST['contact'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        $role = $_POST['role'];
        $is_supervisor = $_POST['is_supervisor'];
        $staff_id = $_POST['staff_id'];
        if(isset($_FILES['image_path'])) {
            $image_path = $_FILES['image_path'];
        } else {
            $image_path = ''; // or set it to some default value as needed
        }
        $response = updateStaffRecords($edit_id, $firstname, $lastname, $middlename, $contact, $designation, $department, $email, $password, $gender, $is_supervisor, $role, $staff_id, $image_path);
        echo $response;

    } elseif ($_POST['action'] === 'staff-add') {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $contact = $_POST['contact'];
        $designation = $_POST['designation'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        $staff_id = $_POST['staff_id'];
        $role = $_POST['role'];
        $is_supervisor = $_POST['is_supervisor'];
        $image_path = $_FILES['image_path'];
        $response = addStaffRecord($firstname, $lastname, $middlename, $contact, $designation, $department, $email, $password, $role, $is_supervisor, $staff_id, $gender, $image_path);
        echo $response;

    } elseif ($_POST['action'] === 'delete-staff') {
        $id = $_POST['id'];
        $response = deleteStaff($id);
        echo $response;
    } elseif (isset($_POST['action']) && $_POST['action'] === 'assign-leave-types') {
        $employeeId = $_POST['employeeId'];
        $leaveTypes = isset($_POST['leaveTypes']) ? $_POST['leaveTypes'] : [];

        // Log received data
        error_log("Received employeeId: " . $employeeId);
        error_log("Received leaveTypes: " . implode(', ', $leaveTypes));

        assignLeaveTypes($employeeId, $leaveTypes);
        
    } elseif ($_POST['action'] === 'assign-supervisor') {
        $employeeId = $_POST['employeeId'];
        $supervisorId = $_POST['supervisorId'];
        $response = assignSupervisor($employeeId, $supervisorId);
        echo $response;
        exit;
    }
}

?>

<?php
// Retrieve the search query and department filter from the AJAX request
$searchQuery = $_POST['searchQuery'];
$departmentFilter = $_POST['departmentFilter'];

$userRole = $_SESSION['srole'];
$userId = $_SESSION['slogin'];
$userDepartment = $_SESSION['department'];
$isSupervisor = $_SESSION['is_supervisor'];

// Generate the SQL query based on the search query and department filter
$sql = "SELECT e.*, d.department_name 
        FROM tblemployees e 
        LEFT JOIN tbldepartments d ON e.department = d.id";

if ($departmentFilter !== '') {
    $sql .= " WHERE d.department_name = '$departmentFilter'";
}
if ($searchQuery !== '') {
    $sql .= ($departmentFilter === '') ? " WHERE" : " AND";
    $sql .= " (e.first_name LIKE '%$searchQuery%' OR e.last_name LIKE '%$searchQuery%' OR e.designation LIKE '%$searchQuery%')";
}

// Execute the SQL query and fetch the staff data
$employeeData = []; // Array to store the fetched staff data
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employeeData[] = $row;
    }
}

// Generate and return the HTML markup for the staff cards
if (empty($employeeData)) {
    echo '<div class="col-lg-12 text-center">
            <img src="../files/assets/images/no_data.png" class="img-radius" alt="No Data Found" style="width: 200px; height: auto;">
          </div>';
} else {
    foreach ($employeeData as $employee) {
        $imagePath = empty($employee['image_path']) ? '../files/assets/images/user-card/img-round1.jpg' : $employee['image_path'];
        echo '<div class="col-lg-6 col-xl-3 col-md-6">
                <div class="card rounded-card user-card">
                    <div class="card-block">
                        <div class="img-hover">
                            <img class="img-fluid img-radius" src="' . $imagePath . '" alt="round-img">
                            <div class="img-overlay img-radius">
                                <span>
                                    <a href="staff_detailed.php?id=' . $employee['emp_id'] . '&view=2" class="btn btn-sm btn-primary" style="margin-top: 1px;" data-popup="lightbox"><i class="icofont icofont-eye-alt"></i></a>';
                                     // Check if the user role is Admin or Manager and the employee's designation is not 'Administrator'
                                    if ($userRole === 'Admin' || ($userRole === 'Manager' && $employee['designation'] !== 'Administrator')) {
                                        echo '<a href="new_staff.php?id=' . $employee['emp_id'] . '&edit=1" class="btn btn-sm btn-primary" data-popup="lightbox" style="margin-left: 8px; margin-top: 1px;"><i class="icofont icofont-edit"></i></a>';
                                        
                                        // Only show the delete icon if the employee's designation is not 'Administrator'
                                        if ($employee['designation'] !== 'Administrator') {
                                            echo '<a href="#" class="btn btn-sm btn-primary delete-staff" style="margin-top: 1px;" data-id="' . $employee['emp_id'] . '"><i class="icofont icofont-ui-delete"></i></a>';
                                        }
                                    }

                                echo '</span>
                            </div>
                        </div>
                        <div class="user-content">
                            <h4 class="">' . $employee['first_name'] . ' ' . $employee['middle_name'] . ' ' . $employee['last_name'] . '</h4>
                            <p class="m-b-0 text-muted">' . $employee['designation'] . '</p>
                        </div>
                    </div>
                </div>
            </div>';
    }
}
?>
<!-- staff_detailed.php -->