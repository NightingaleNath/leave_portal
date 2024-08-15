
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

// Get the logged-in user ID
$userId = $_SESSION['slogin'];

$stmt = $conn->prepare("SELECT COUNT(*) as total_leave FROM tblleave WHERE empid = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_leave = $row['total_leave'];

// Fetch the count of pending leaves
$stmt = $conn->prepare("SELECT COUNT(*) as pending_leave FROM tblleave WHERE empid = ? AND leave_status = 0");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$pending_leave = $row['pending_leave'];

// Fetch the count of approved leaves
$stmt = $conn->prepare("SELECT COUNT(*) as approved_leave FROM tblleave WHERE empid = ? AND leave_status = 1");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$approved_leave = $row['approved_leave'];

// Fetch the count of recalled leaves
$stmt = $conn->prepare("SELECT COUNT(*) as recalled_leave FROM tblleave WHERE empid = ? AND leave_status = 3");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$recalled_leave = $row['recalled_leave'];

// Fetch the count of canceled leaves
$stmt = $conn->prepare("SELECT COUNT(*) as rejected_leave FROM tblleave WHERE empid = ? AND leave_status = 4");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$rejected_leave = $row['rejected_leave'];

// Calculate the percentages
$pending_percentage = ($total_leave > 0) ? floor(($pending_leave / $total_leave) * 100) : 0;
$approved_percentage = ($total_leave > 0) ? floor(($approved_leave / $total_leave) * 100) : 0;
$recalled_percentage = ($total_leave > 0) ? floor(($recalled_leave / $total_leave) * 100) : 0;
$rejected_percentage = ($total_leave > 0) ? floor(($rejected_leave / $total_leave) * 100) : 0;

?>

<?php
$totalStaff = 0;

// Assuming you have a database connection, fetch all departments
$departmentQuery = $conn->prepare("SELECT * FROM tbldepartments");
$departmentQuery->execute();
$departmentResult = $departmentQuery->get_result();

$departments = [];

while ($departmentRow = $departmentResult->fetch_assoc()) {
    $departmentId = $departmentRow['id'];
    $departmentName = $departmentRow['department_name'];
    $departmentDesc = $departmentRow['department_desc'];

    // Fetch the count of staff in the department
    $staffQuery = $conn->prepare("SELECT COUNT(*) as staff_count FROM tblemployees WHERE department = ?");
    $staffQuery->bind_param("i", $departmentId);
    $staffQuery->execute();
    $staffResult = $staffQuery->get_result();
    $staffRow = $staffResult->fetch_assoc();
    $staffCount = $staffRow['staff_count'];

    $totalStaff += $staffCount;

    // Fetch the count of managers in the department
    $managerQuery = $conn->prepare("SELECT COUNT(*) as manager_count FROM tblemployees WHERE department = ? AND role = 'Manager'");
    $managerQuery->bind_param("i", $departmentId);
    $managerQuery->execute();
    $managerResult = $managerQuery->get_result();
    $managerRow = $managerResult->fetch_assoc();
    $managerCount = $managerRow['manager_count'];

    $departments[] = [
        'id' => $departmentId,
        'name' => $departmentName,
        'desc' => $departmentDesc,
        'staffCount' => $staffCount,
        'managerCount' => $managerCount,
    ];
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
                    <?php $page_name = "dashboard"; ?>
                    <?php include('../includes/sidebar.php')?>
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <!-- user card  start -->
                                            <div class="col-md-6 col-xl-3">
                                                <div class="card widget-card-1">
                                                    <?php
                                                        $stmt = $conn->prepare("SELECT COUNT(*) as total_employee FROM tblemployees");
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_assoc();
                                                        $total_employee = $row['total_employee'];    
                                                    ?>
                                                    <div class="card-block-small">
                                                        <i class="feather icon-user bg-c-blue card1-icon"></i>
                                                        <span class="text-c-blue f-w-600">Active Staff</span>
                                                        <?php if ($total_employee == 0): ?>
                                                            <h4>No</h4>
                                                        <?php else: ?>
                                                            <h4><?= $total_employee ?></h4>
                                                        <?php endif; ?>
                                                        <div>
                                                            <span class="f-left m-t-10 text-muted">
                                                                <i class="text-c-blue f-16 feather icon-user m-r-10"></i>Registered Staff
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xl-3">
                                                <div class="card widget-card-1">
                                                    <?php
                                                        $stmt = $conn->prepare("SELECT COUNT(*) as total_depart FROM tbldepartments");
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_assoc();
                                                        $total_depart = $row['total_depart'];    
                                                    ?>
                                                    <div class="card-block-small">
                                                        <i class="feather icon-home bg-c-pink card1-icon"></i>
                                                        <span class="text-c-pink f-w-600">Departments</span>
                                                        <?php if ($total_depart == 0): ?>
                                                            <h4>No</h4>
                                                        <?php else: ?>
                                                            <h4><?= $total_depart ?></h4>
                                                        <?php endif; ?>
                                                        <div>
                                                            <span class="f-left m-t-10 text-muted">
                                                                <i class="text-c-pink f-16 feather icon-home m-r-10"></i>Available Departments
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xl-3">
                                                <div class="card widget-card-1">
                                                    <?php
                                                        $stmt = $conn->prepare("SELECT COUNT(*) as total_types FROM tblleavetype");
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_assoc();
                                                        $total_types = $row['total_types'];    
                                                    ?>
                                                    <div class="card-block-small">
                                                        <i class="feather icon-tag bg-c-green card1-icon"></i>
                                                        <span class="text-c-green f-w-600">Leave Types</span>
                                                        <?php if ($total_types == 0): ?>
                                                            <h4>No</h4>
                                                        <?php else: ?>
                                                            <h4><?= $total_types ?></h4>
                                                        <?php endif; ?>
                                                        <div>
                                                            <span class="f-left m-t-10 text-muted">
                                                                <i class="text-c-green f-16 feather icon-tag m-r-10"></i>Active Leave Types
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xl-3">
                                                <div class="card widget-card-1">
                                                    <?php
                                                        $stmt = $conn->prepare("SELECT COUNT(*) as total_leave FROM tblleave");
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_assoc();
                                                        $total_leave = $row['total_leave'];    
                                                    ?>
                                                    <div class="card-block-small">
                                                        <i class="feather icon-list bg-c-yellow card1-icon"></i>
                                                        <span class="text-c-yellow f-w-600">Leave</span>
                                                        <?php if ($total_leave == 0): ?>
                                                            <h4>No</h4>
                                                        <?php else: ?>
                                                            <h4><?= $total_leave ?></h4>
                                                        <?php endif; ?>
                                                        <div>
                                                            <span class="f-left m-t-10 text-muted">
                                                                <i class="text-c-yellow f-16 feather icon-list m-r-10"></i>Leave Application
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- user card  end -->

                                             <!-- statustic with progressbar  start -->
                                             <div class="col-xl-3 col-md-6">
                                                <div class="card statustic-progress-card">
                                                    <div class="card-header">
                                                        <h5>Pending Leave</h5>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <label class="label bg-c-lite-green">
                                                                    <?php echo $pending_percentage; ?>% <i class="m-l-10 feather icon-arrow-up"></i>
                                                                </label>
                                                            </div>
                                                            <div class="col text-right">
                                                                <h5 class=""><?php echo $pending_leave; ?></h5>
                                                            </div>
                                                        </div>
                                                        <div class="progress m-t-15">
                                                            <div class="progress-bar bg-c-lite-green" style="width:<?php echo $pending_percentage; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card statustic-progress-card">
                                                    <div class="card-header">
                                                        <h5>Approved Leave</h5>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <label class="label label-success">
                                                                    <?php echo $approved_percentage; ?>% <i class="m-l-10 feather icon-arrow-up"></i>
                                                                </label>
                                                            </div>
                                                            <div class="col text-right">
                                                                <h5 class=""><?php echo $approved_leave; ?></h5>
                                                            </div>
                                                        </div>
                                                        <div class="progress m-t-15">
                                                            <div class="progress-bar bg-c-green" style="width:<?php echo $approved_percentage; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card statustic-progress-card">
                                                    <div class="card-header">
                                                        <h5>Rejected Leave</h5>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <label class="label label-danger">
                                                                    <?php echo $rejected_percentage; ?>% <i class="m-l-10 feather icon-arrow-up"></i>
                                                                </label>
                                                            </div>
                                                            <div class="col text-right">
                                                                <h5 class=""><?php echo $rejected_leave; ?></h5>
                                                            </div>
                                                        </div>
                                                        <div class="progress m-t-15">
                                                            <div class="progress-bar bg-c-pink" style="width:<?php echo $rejected_percentage; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6">
                                                <div class="card statustic-progress-card">
                                                    <div class="card-header">
                                                        <h5>Recalled Leave</h5>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <label class="label label-warning">
                                                                    <?php echo $recalled_percentage; ?>% <i class="m-l-10 feather icon-arrow-up"></i>
                                                                </label>
                                                            </div>
                                                            <div class="col text-right">
                                                                <h5 class=""><?php echo $recalled_leave; ?></h5>
                                                            </div>
                                                        </div>
                                                        <div class="progress m-t-15">
                                                            <div class="progress-bar bg-c-yellow" style="width:<?php echo $recalled_percentage; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- statustic with progressbar  end -->

                                            <!-- Department  start -->
                                            <?php foreach ($departments as $department): ?>
                                            <div class="col-md-12 col-xl-6 ">
                                                <div class="card app-design">
                                                    <div class="card-block">
                                                        <a href="staff_list.php?department=<?= urlencode($department['name']) ?>"><button class="btn btn-primary f-right"><?= $department['name'] ?></button></a>
                                                        <h6 class="f-w-400 text-muted"><?= $department['desc'] ?></h6>
                                                        <div class="design-description d-inline-block m-r-40">
                                                            <?php if ($department['staffCount'] > 0): ?>
                                                                <h3 class="f-w-400"><?= $department['staffCount'] ?></h3>
                                                            <?php else: ?>
                                                                <h5>No</h5>
                                                            <?php endif; ?>
                                                            <p class="text-muted">Total Staff</p>
                                                        </div>
                                                        <div class="design-description d-inline-block">
                                                            <?php if ($department['managerCount'] > 0): ?>
                                                                <h3 class="f-w-400"><?= $department['managerCount'] ?></h3>
                                                            <?php else: ?>
                                                                <h5>No</h5>
                                                            <?php endif; ?>
                                                            <p class="text-muted">Total Managers</p>
                                                        </div>
                                                        <div class="team-box p-b-20">
                                                            <p class="d-inline-block m-r-20 f-w-400">
                                                                <?php
                                                                if ($department['staffCount'] > 0) {
                                                                    echo "Team";
                                                                } else {
                                                                    echo "No Staff";
                                                                }
                                                                ?>
                                                            </p>
                                                            <div class="team-section d-inline-block">
                                                                <?php
                                                                // Fetch and display only 10 staff members for this department
                                                                $staffQuery = $conn->prepare("SELECT * FROM tblemployees WHERE department = ? LIMIT 10");
                                                                $staffQuery->bind_param("i", $department['id']);
                                                                $staffQuery->execute();
                                                                $staffResult = $staffQuery->get_result();

                                                                while ($staffRow = $staffResult->fetch_assoc()) {
                                                                    $staffImage = $staffRow['image_path'];
                                                                    $staffName = $staffRow['first_name'] . ' ' . $staffRow['last_name'];
                                                                    echo "<a href='#!'><img src='$staffImage' data-toggle='tooltip' title='$staffName' alt='' class='m-l-5 '></a>";
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="progress-box">
                                                            <p class="d-inline-block m-r-20 f-w-400">Progress</p>
                                                            <div class="progress d-inline-block">
                                                                 <?php
                                                                   $staffPercentage = $totalStaff > 0 ? round(($department['staffCount'] / $totalStaff) * 100) : 0;
                                                                 ?>
                                                                <div class="progress-bar bg-c-blue" style="width:<?= $staffPercentage ?>% "><label><?= $staffPercentage ?>%</label></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                            <!-- Department  end -->
                                                    
                                        </div>
                                    </div>
                                    <!-- Page-body end -->
                                    
                                </div>
                                <div id="styleSelector"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Jquery -->
    <?php include('../includes/scripts.php')?>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
</body>

</html>