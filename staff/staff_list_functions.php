<?php
date_default_timezone_set('Africa/Accra');
session_start();
include('../includes/config.php');

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
                                    <a href="staff_detailed.php?id=' . $employee['emp_id'] . '&view=2" class="btn btn-sm btn-primary" data-popup="lightbox"><i class="icofont icofont-eye-alt"></i></a>';
                                     // Check if the user role is Admin or Manager
                                    if ($userRole === 'Admin' || ($userRole === 'Manager' && $employee['designation'] !== 'Administrator')) {
                                        echo '<a href="new_staff.php?id=' . $employee['emp_id'] . '&edit=1" class="btn btn-sm btn-primary" data-popup="lightbox" style="margin-left: 5px;"><i class="icofont icofont-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-primary delete-staff" data-id="' . $employee['emp_id'] . '"><i class="icofont icofont-ui-delete"></i></a>';
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