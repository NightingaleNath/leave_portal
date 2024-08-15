<?php include('../includes/header.php')?>

<?php
// Check if the user is logged in
if (!isset($_SESSION['slogin']) || !isset($_SESSION['srole'])) {
    header('Location: ../index.php');
    exit();
}

// Check if the user has the role of Manager or Admin
$userRole = $_SESSION['srole'];
if ($userRole !== 'Staff') {
    header('Location: ../index.php');
    exit();
}
?>

<body>
<!-- Pre-loader start -->
 <?php include('../includes/loader.php')?>
<!-- Pre-loader end -->
<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

        <?php include('../includes/topbar.php')?>

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">

                <?php $page_name = "staff_list"; ?>
                <?php include('../includes/sidebar.php')?>
                
                <div class="pcoded-content">
                    <div class="pcoded-inner-content">
                        <!-- Main-body start -->
                        <div class="main-body">
                            <div class="page-wrapper">
                                <!-- Page-header start -->
                                <div class="page-header">
                                    <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <div class="page-header-title">
                                                <div class="d-inline">
                                                    <?php
                                                        $get_id = isset($_GET['id']) ? $_GET['id'] : null;
                                                        $profileText = ($session_id == $get_id) ? "My Profile" : "Staff Profile";
                                                    ?>
                                                    <h4><?= htmlspecialchars($profileText) ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-header end -->

                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <!--profile cover start-->

                                        <?php
                                            // Check if the edit parameter is set and fetch the record from the database
                                            if(isset($_GET['view']) && $_GET['view'] == 2 && isset($_GET['id'])) {
                                                $id = $_GET['id'];
                                                $stmt = mysqli_prepare($conn, "SELECT * FROM tblemployees WHERE emp_id = ?");
                                                mysqli_stmt_bind_param($stmt, "i", $id);
                                                mysqli_stmt_execute($stmt);
                                                $result = mysqli_stmt_get_result($stmt);
                                                $row = mysqli_fetch_assoc($result);
                                            }
                                           
                                        ?>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="cover-profile">
                                                    <div class="profile-bg-img">
                                                        <img class="profile-bg-img img-fluid" src="..\files\assets\images\user-profile\bg-img1.jpg" alt="bg-img">
                                                        <div class="card-block user-info">
                                                            <div class="col-md-12">
                                                                <div class="media-left">
                                                                    <a class="profile-image">
                                                                         <img class="user-img img-radius" style="width: 108px; height: 108px;" src="<?php echo isset($row['image_path']) ? htmlspecialchars($row['image_path']) : '../files/assets/images/user-card/img-round1.jpg'; ?>" alt="user-img">
                                                                    </a>
                                                                </div>
                                                                <div class="media-body row">
                                                                    <div class="col-lg-12">
                                                                        <div class="user-title">
                                                                            <h2><?php echo htmlspecialchars($row['first_name'] . ' '.$row['middle_name'] . ' ' . $row['last_name']); ?></h2>
                                                                            <span class="text-white"><?php echo htmlspecialchars($row['designation']); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($session_id == $get_id): ?>
                                                                        <div class="pull-right cover-btn">
                                                                            <button type="button" class="btn btn-primary m-r-10 m-b-5" data-toggle="modal" data-target="#change-password-dialog"> Change Password</button>
                                                                        </div>
                                                                    <?php endif; ?>  
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--profile cover end-->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <!-- tab header start -->
                                                <div class="tab-header card">
                                                    <ul class="nav nav-tabs md-tabs tab-timeline" role="tablist" id="mytab">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">Personal Info</a>
                                                            <div class="slide"></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- tab header end -->
                                                <!-- tab content start -->
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="personal" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-xl-3">
                                                                <!-- user contact card left side start -->
                                                                <div class="card">
                                                                    <div class="card-block groups-contact">
                                                                        <?php
                                                                            $employeeId = $row['emp_id'];

                                                                            // Fetch the supervisor details
                                                                            $supervisorQuery = "
                                                                                SELECT s.emp_id, s.first_name, s.middle_name, s.last_name
                                                                                FROM tblemployees e
                                                                                JOIN tblemployees s ON e.supervisor_id = s.emp_id
                                                                                WHERE e.emp_id = ?
                                                                            ";

                                                                            $stmt = $conn->prepare($supervisorQuery);
                                                                            $stmt->bind_param('i', $employeeId);
                                                                            $stmt->execute();
                                                                            $result = $stmt->get_result();

                                                                            $supervisor = $result->fetch_assoc();

                                                                            $userRole = $_SESSION['srole'];

                                                                            // Fetch the designation of the currently viewed employee
                                                                            $designationQuery = "
                                                                                SELECT designation
                                                                                FROM tblemployees
                                                                                WHERE emp_id = ?
                                                                            ";

                                                                            $stmt = $conn->prepare($designationQuery);
                                                                            $stmt->bind_param('i', $employeeId);
                                                                            $stmt->execute();
                                                                            $result = $stmt->get_result();

                                                                            $designation = $result->fetch_assoc()['designation'];
                                                                        ?>
                                                                        <div class="card-header">
                                                                            <h5 class="card-header-text">Assigned Supervisor</h5>
                                                                            <?php if (($userRole === 'Admin' || $userRole === 'Manager') && !($userRole === 'Manager' && $designation === 'Administrator')): ?>
                                                                                <button data-toggle="modal" data-target="#edit-supervisor" type="button" class="btn btn-sm btn-primary waves-effect waves-light f-right">
                                                                                    <i class="icofont icofont-settings"></i>
                                                                                </button>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <ul class="list-group">
                                                                            <?php if ($supervisor): ?>
                                                                                <li class="list-group-item justify-content-between">
                                                                                    <?php echo htmlspecialchars($supervisor['first_name'] . ' ' . $supervisor['middle_name'] . ' ' . $supervisor['last_name']); ?>
                                                                                </li>
                                                                            <?php else: ?>
                                                                                <li class="list-group-item justify-content-between">
                                                                                    No supervisor assigned.
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="card-block groups-contact">
                                                                        <?php
                                                                            // Fetch assigned leave types for the employee
                                                                            $assignedLeaveTypesQuery = "
                                                                                SELECT lt.leave_type, lt.assign_days, elt.available_days
                                                                                FROM tblleavetype lt
                                                                                INNER JOIN employee_leave_types elt ON lt.id = elt.leave_type_id
                                                                                WHERE elt.emp_id = ?
                                                                            ";

                                                                            $stmt = $conn->prepare($assignedLeaveTypesQuery);
                                                                            $stmt->bind_param('i', $employeeId);
                                                                            $stmt->execute();
                                                                            $result = $stmt->get_result();

                                                                            $assignedLeaveTypes = [];
                                                                            while ($newRow = $result->fetch_assoc()) {
                                                                                $assignedLeaveTypes[] = $newRow;
                                                                            }
                                                                        ?>
                                                                        <div class="card-header">
                                                                            <h5 class="card-header-text">Leave Credits</h5>
                                                                            <?php if (($userRole === 'Admin' || $userRole === 'Manager') && !($userRole === 'Manager' && $designation === 'Administrator')): ?>
                                                                                <button data-toggle="modal" data-target="#edit-leave-type" type="button" class="btn btn-sm btn-primary waves-effect waves-light f-right">
                                                                                    <i class="icofont icofont-settings"></i>
                                                                                </button>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div class="card-block table-border-style">
                                                                            <div class="table-responsive">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Leave Type</th>
                                                                                            <th>Allowed</th>
                                                                                            <th>Available</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php if (!empty($assignedLeaveTypes)): ?>
                                                                                            <?php foreach ($assignedLeaveTypes as $leaveType): ?>
                                                                                                <tr>
                                                                                                    <td><?php echo htmlspecialchars($leaveType['leave_type']); ?></td>
                                                                                                    <td><?php echo htmlspecialchars($leaveType['assign_days']); ?></td>
                                                                                                    <td><?php echo htmlspecialchars($leaveType['available_days']); ?></td>
                                                                                                </tr>
                                                                                            <?php endforeach; ?>
                                                                                        <?php else: ?>
                                                                                            <tr>
                                                                                                <td colspan="3" class="text-center">No leave types assigned yet.</td>
                                                                                            </tr>
                                                                                        <?php endif; ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- user contact card left side end -->
                                                            </div>
                                                            <div class="col-xl-9">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <!-- contact data table card start -->
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                <h5 class="card-header-text">About Staff</h5>
                                                                            </div>
                                                                             <div class="card-block">
                                                                                <div class="view-info">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-12">
                                                                                            <div class="general-info">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-12 col-xl-6">
                                                                                                        <div class="table-responsive">
                                                                                                            <table class="table m-0">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Full Name</th>
                                                                                                                        <td><?php echo htmlspecialchars($row['first_name'] . ' '.$row['middle_name'] . ' ' . $row['last_name']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Gender</th>
                                                                                                                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Date Created</th>
                                                                                                                        <td><?php echo htmlspecialchars(date('jS F, Y', strtotime($row['date_created']))); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Designation</th>
                                                                                                                        <td><?php echo htmlspecialchars($row['designation']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Is Supervisor?</th>
                                                                                                                       <td><?php echo htmlspecialchars($row['is_supervisor'] == 1 ? 'Yes' : 'No'); ?></td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <!-- end of table col-lg-6 -->
                                                                                                    <div class="col-lg-12 col-xl-6">
                                                                                                        <div class="table-responsive">
                                                                                                            <table class="table">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Email</th>
                                                                                                                        <td><a href="#!"><span class="__cf_email__" data-cfemail="4206272f2d02273a232f322e276c212d2f"><?php echo htmlspecialchars($row['email_id']); ?>&#160;</span></a></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Mobile Number</th>
                                                                                                                        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Staff ID</th>
                                                                                                                        <td><?php echo htmlspecialchars($row['staff_id']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Role</th>
                                                                                                                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <th scope="row">Department</th>
                                                                                                                          <?php
                                                                                                                            $stmt = mysqli_prepare($conn, "SELECT id, department_name FROM tbldepartments WHERE id = ?");
                                                                                                                            mysqli_stmt_bind_param($stmt, "i", $row['department']);
                                                                                                                            mysqli_stmt_execute($stmt);
                                                                                                                            mysqli_stmt_bind_result($stmt, $id, $name);
                                                                                                                            mysqli_stmt_fetch($stmt);
                                                                                                                            mysqli_stmt_close($stmt);
                                                                                                                            echo '<td>' . $name . '</td>';
                                                                                                                         ?>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <!-- end of table col-lg-6 -->
                                                                                                </div>
                                                                                                <!-- end of row -->
                                                                                            </div>
                                                                                            <!-- end of general info -->
                                                                                        </div>
                                                                                        <!-- end of col-lg-12 -->
                                                                                    </div>
                                                                                    <!-- end of row -->
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- contact data table card end -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                                <!-- tab content end -->

                                                <!-- Modal Leave Type in start -->
                                                <?php
                                                    $employeeId = $row['emp_id']; 

                                                    // Fetch all leave types
                                                    $leaveTypesQuery = "SELECT * FROM tblleavetype where status = '1'";
                                                    $leaveTypesResult = mysqli_query($conn, $leaveTypesQuery);

                                                    // Fetch assigned leave types for the employee
                                                    $assignedLeaveTypesQuery = "SELECT leave_type_id, available_days FROM employee_leave_types WHERE emp_id = $employeeId";
                                                    $assignedLeaveTypesResult = mysqli_query($conn, $assignedLeaveTypesQuery);
                                                    $assignedLeaveTypes = [];
                                                    while ($newRow = mysqli_fetch_assoc($assignedLeaveTypesResult)) {
                                                        $assignedLeaveTypes[$newRow['leave_type_id']] = $newRow['available_days'];
                                                    }
                                                ?>
                                                <div id="edit-leave-type" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="login-card card-block login-card-modal">
                                                            <form class="md-float-material">
                                                                <div class="card m-t-15">
                                                                <div class="auth-box card-block">
                                                                    <div class="row m-b-20">
                                                                        <div class="col-md-12">
                                                                            <h5 class="text-center txt-primary">Manage Leave Credits of <strong><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']); ?></strong></h5>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="card-block groups-contact">
                                                                        <ul class="list-group">
                                                                            <li class="list-group-item justify-content-between">
                                                                                <div class="checkbox-fade fade-in-primary">
                                                                                    <label>
                                                                                        <input type="checkbox" id="selectAllLeaveTypes">
                                                                                        <span class="cr">
                                                                                            <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                        </span>
                                                                                    </label>
                                                                                    <span class="text" style="margin-left: 15px;">Leave Types</span>
                                                                                </div>
                                                                                <span class="text" style="margin-left: 15px;">Leave Credits</span>
                                                                            </li>
                                                                            <?php while ($leaveType = mysqli_fetch_assoc($leaveTypesResult)): ?>
                                                                                <?php
                                                                                    $isChecked = array_key_exists($leaveType['id'], $assignedLeaveTypes);
                                                                                    $isDisabled = $isChecked && $assignedLeaveTypes[$leaveType['id']] != $leaveType['assign_days'];
                                                                                ?>
                                                                                <li class="list-group-item justify-content-between">
                                                                                    <div class="checkbox-fade fade-in-primary">
                                                                                        <label>
                                                                                            <input type="checkbox" name="leaveTypes[]" value="<?php echo $leaveType['id']; ?>"
                                                                                                <?php echo $isChecked ? 'checked' : ''; ?>
                                                                                                <?php echo $isDisabled ? 'disabled' : ''; ?>>
                                                                                            <span class="cr">
                                                                                                <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                            </span>
                                                                                        </label>
                                                                                        <span class="text" style="margin-left: 15px;"><?php echo $leaveType['leave_type']; ?></span>
                                                                                    </div>
                                                                                    <span class="badge badge-inverse-info"><?php echo $leaveType['assign_days']; ?></span>
                                                                                </li>
                                                                            <?php endwhile; ?>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="row m-t-15">
                                                                        <div class="col-md-12">
                                                                            <button type="button" id="saveLeaveTypesBtn" class="btn btn-primary btn-md btn-block waves-effect text-center">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-md-10">
                                                                             <p class="text-inverse text-center m-b-0">You can assign different types of leave to your staff members.</p>
                                                                            <p class="text-inverse text-left text-warning"><b>Note:</b> Leave types that have been used by the employee cannot be reassigned and will be disabled.</p>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <!-- <img src="..\files\assets\images\auth\Logo-small-bottom.png" alt="small-logo.png"> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </form>
                                                            <!-- end of form -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal Leave Type in end-->

                                                <!-- Modal Assign Supervisor in start -->
                                                <div id="edit-supervisor" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="login-card card-block login-card-modal">
                                                            <form class="md-float-material">
                                                                <div class="card m-t-15">
                                                                <div class="auth-box card-block">
                                                                    <div class="row m-b-20">
                                                                        <div class="col-md-12">
                                                                            <h5 class="text-center txt-primary">Manage Supervisor <strong><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']); ?></strong></h5>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <select name="supervisor" class="form-control form-control-info">
                                                                        <option value="">Select One Value Only</option>
                                                                        <?php
                                                                            $employeeId = $row['emp_id'];
                                                                            
                                                                            // Fetch the employee's department
                                                                            $employeeDeptQuery = "SELECT department FROM tblemployees WHERE emp_id = ?";
                                                                            $stmt = $conn->prepare($employeeDeptQuery);
                                                                            $stmt->bind_param('i', $employeeId);
                                                                            $stmt->execute();
                                                                            $result = $stmt->get_result();
                                                                            $employeeDeptRow = $result->fetch_assoc();
                                                                            $employeeDept = $employeeDeptRow['department'];

                                                                            // Fetch supervisors from the database excluding Admins and the current employee, and applying the department condition
                                                                            $supervisorQuery = "
                                                                                SELECT emp_id, first_name, middle_name, last_name 
                                                                                FROM tblemployees 
                                                                                WHERE is_supervisor = 1 
                                                                                AND role != 'Admin' 
                                                                                AND emp_id != ? 
                                                                                AND (role = 'Manager' OR department = ?)
                                                                            ";
                                                                            $stmt = $conn->prepare($supervisorQuery);
                                                                            $stmt->bind_param('is', $employeeId, $employeeDept);
                                                                            $stmt->execute();
                                                                            $supervisorResult = $stmt->get_result();
                                                                            
                                                                            while ($supervisorRow = $supervisorResult->fetch_assoc()) {
                                                                                $supervisorId = htmlspecialchars($supervisorRow['emp_id']);
                                                                                $supervisorName = htmlspecialchars($supervisorRow['first_name'] . ' ' . $supervisorRow['middle_name'] . ' ' . $supervisorRow['last_name']);
                                                                                echo "<option value=\"$supervisorId\">$supervisorName</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    <div class="row m-t-15">
                                                                        <div class="col-md-12">
                                                                            <button type="button" id="assignSupervisorBtn" class="btn btn-primary btn-md btn-block waves-effect text-center">Assign Supervisor</button>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-md-10">
                                                                            <p class="text-inverse text-left m-b-0">You can assign supervisors to your staff members.</p>
                                                                            <p class="text-inverse text-left"><b>Please select the appropriate supervisor for each employee.</b></p>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <!-- <img src="..\files\assets\images\auth\Logo-small-bottom.png" alt="small-logo.png"> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </form>
                                                            <!-- end of form -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal Assign Supervisor in end-->

                                            </div>
                                        </div>
                                    </div>
                                    <!-- Page-body end -->
                                </div>
                            </div>
                            <!-- Change password modal start -->
                            <div id="change-password-dialog" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="login-card card-block login-card-modal">
                                        <form class="md-float-material">
                                            <div class="card m-t-15">
                                                <div class="auth-box card-block">
                                                <div class="row m-b-20">
                                                    <div class="col-md-12">
                                                        <h3 class="text-center txt-primary">Change your Password</h3>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="input-group">
                                                    <input id="old_password" type="password" class="form-control" placeholder="Old Password">
                                                    <span class="md-line"></span>
                                                </div>
                                                <div class="input-group">
                                                    <input id="new_password" type="password" class="form-control" placeholder="New Password">
                                                    <span class="md-line"></span>
                                                </div>
                                                <div class="input-group">
                                                    <input id="confirm_password" type="password" class="form-control" placeholder="Confirm New Password">
                                                    <span class="md-line"></span>
                                                </div>
                                                <div class="row m-t-15">
                                                    <div class="col-md-12">
                                                        <button id="change_password" type="button" class="btn btn-primary btn-md btn-block waves-effect text-center">Change</button>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <p class="text-inverse text-left"><b>You will be authenticated after password reset.</b></p>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                        <!-- end of form -->
                                    </div>
                                </div>
                            </div>
                            <!-- Change password modal end-->
                            <!-- Main body end -->
                            <div id="styleSelector">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Required Jquery -->
<?php include('../includes/scripts.php')?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-23581568-13');
</script>

<script type="text/javascript">
        $('#change_password').click(function(event) {
            event.preventDefault();
            $('.modal').css('z-index', '1050');
            
            (async () => {
                var data = {
                    old_password: $('#old_password').val(),
                    new_password: $('#new_password').val(),
                    confirm_password: $('#confirm_password').val(),
                    action: "change_password",
                };

                if (data.old_password.trim() === '' || data.new_password.trim() === '' || data.confirm_password.trim() === '') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in all fields.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (data.new_password !== data.confirm_password) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'New password and confirmation password do not match.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                $.ajax({
                    url: '../admin/password_functions.php',
                    type: 'post',
                    data: data,
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Password Reset Successfully',
                                text: 'Your password has been reset successfully. Kindly login again',
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('.md-close').trigger('click');
                                    window.location = '../logout.php';
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            })()
        });
    </script>

</body>

</html>
