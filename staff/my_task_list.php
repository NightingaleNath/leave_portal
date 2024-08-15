<?php include('../includes/header.php')?>
<?php include('../includes/utils.php')?>
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
    <Style>
        .faq-progress .progress {
            height: 8px;
            background-color: #e8e8e8;
            border-radius: 50px;
            overflow: hidden;
        }

        .faq-progress .faq-test3 {
            height: 10px;
            border-radius: 50px;
            transition: width 0.5s ease-in-out;
        }
        .faq-bar-highest {
            background-color: #eb3422;
        }

        .faq-bar-high {
            background-color: #fe9365;
        }

        .faq-bar-normal {
            background-color: #0ac282;
        }

        .faq-bar-low {
            background-color: #01a9ac;
        }

    </Style>
    
<!-- Pre-loader start -->
<?php include('../includes/loader.php')?>
<!-- Pre-loader end -->
<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

        <?php include('../includes/topbar.php')?>

        <!-- Sidebar inner chat end-->
        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">

                <?php $page_name = "my_task_list"; ?>
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
                                                    <h4>My Task List</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="page-header-breadcrumb">
                                                <ul class="breadcrumb-title">
                                                    <li class="breadcrumb-item">
                                                        <a href="#"> <i class="feather icon-home"></i> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-header end -->

                                    <!-- Page body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <!-- Right column start -->
                                            <div class="col-xl-3 col-lg-12 push-xl-9">
                                                <!-- Search box card start -->
                                                <div class="card">
                                                    <div class="card-block p-t-10">
                                                        <div class="task-right">
                                                            <div class="task-right-header-status">
                                                                <span data-toggle="collapse">Task Status</span>
                                                                <i class="icofont icofont-rounded-down f-right"></i>
                                                            </div>
                                                            <!-- end of sidebar-header completed status-->
                                                            <div class="taskboard-right-progress">
                                                                <h6>High Priority</h6>
                                                                <div class="faq-progress">
                                                                    <div class="progress">
                                                                        <!-- <span class="faq-text1"></span> -->
                                                                        <div class="faq-test3 faq-bar-high" style="width: 70%;"></div>
                                                                    </div>
                                                                </div>
                                                                <h6>Medium Priority</h6>
                                                                <div class="faq-progress">
                                                                    <div class="progress">
                                                                        <!-- <span class="faq-text2"></span> -->
                                                                        <div class="faq-test3 faq-bar-normal" style="width: 50%;"></div>
                                                                    </div>
                                                                </div>
                                                                <h6>Low Priority</h6>
                                                                <div class="faq-progress">
                                                                    <div class="progress">
                                                                        <!-- <span class="faq-text4"></span> -->
                                                                        <div class="faq-test3 faq-bar-low" style="width: 60%;"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- end of task-board-right progress -->
                                                        </div>
                                                        <!-- end of sidebar-right -->
                                                    </div>
                                                    <!-- end of card-block -->
                                                </div>
                                                <!-- Search box card end -->
                                            </div>
                                            <!-- Right column end -->
                                            <!-- Left column start -->
                                            <div class="col-xl-9 col-lg-12 pull-xl-3 filter-bar">
                                                <!-- Nav Filter tab start -->
                                                <?php
                                                    $status = isset($_GET['status']) ? $_GET['status'] : null;
                                                    $timeRange = isset($_GET['timeRange']) ? $_GET['timeRange'] : null;
                                                    $sessionEmpId = $_SESSION['slogin'];
                                                    $isSupervisor = $_SESSION['is_supervisor'] == 1; // Check if the user is a supervisor

                                                    // Base query
                                                     $query = "SELECT t.id, t.title, t.description, t.status, t.priority, t.created_at, t.start_date, t.due_date, 
                                                                        e.first_name AS assigned_to_first_name, e.last_name AS assigned_to_last_name, 
                                                                        e.middle_name AS assigned_to_middle_name, e.image_path AS assigned_to_profile,
                                                                        em.first_name AS assigned_by_first_name, em.last_name AS assigned_by_last_name, 
                                                                        em.middle_name AS assigned_by_middle_name, em.image_path AS assigned_by_profile,
                                                                        t.assigned_to, t.assigned_by, e.supervisor_id
                                                                FROM tbltask t
                                                                JOIN tblemployees e ON t.assigned_to = e.emp_id
                                                                JOIN tblemployees em ON t.assigned_by = em.emp_id";

                                                    // Add WHERE clause based on user role
                                                    $where = "";
                                                    if ($isSupervisor) {
                                                        // If the user is a supervisor, filter by supervisor_id
                                                        $where .= " WHERE (e.supervisor_id = ? OR t.assigned_to = ?)";
                                                    } else {
                                                        // If the user is a staff member, filter by assigned_to
                                                        $where .= " WHERE t.assigned_to = ?";
                                                    }

                                                    // Additional filters
                                                    if (isset($timeRange)) {
                                                        switch ($timeRange) {
                                                            case 'today':
                                                                $where .= " AND DATE(t.created_at) = CURDATE()";
                                                                break;
                                                            case 'yesterday':
                                                                $where .= " AND DATE(t.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                                                                break;
                                                            case 'this-week':
                                                                $where .= " AND WEEK(t.created_at) = WEEK(NOW()) AND YEAR(t.created_at) = YEAR(NOW())";
                                                                break;
                                                            case 'this-month':
                                                                $where .= " AND MONTH(t.created_at) = MONTH(NOW()) AND YEAR(t.created_at) = YEAR(NOW())";
                                                                break;
                                                            case 'this-year':
                                                                $where .= " AND YEAR(t.created_at) = YEAR(NOW())";
                                                                break;
                                                            default:
                                                                break;
                                                        }
                                                    }

                                                    if ($status !== null) {
                                                        switch ($status) {
                                                            case 'Pending':
                                                                $statusWhere = "t.status = 'Pending'";
                                                                break;
                                                            case 'In Progress':
                                                                $statusWhere = "t.status = 'In Progress'";
                                                                break;
                                                            case 'Completed':
                                                                $statusWhere = "t.status = 'Completed'";
                                                                break;
                                                            default:
                                                                $statusWhere = "";
                                                                break;
                                                        }
                                                        if ($statusWhere) {
                                                            $where .= " AND " . $statusWhere;
                                                        }
                                                    }

                                                    $query .= $where;
                                                    $query .= " ORDER BY t.created_at DESC";

                                                    // Prepare and execute the query
                                                    $stmt = mysqli_prepare($conn, $query);
                                                    if (!$stmt) {
                                                        die('Error preparing statement: ' . mysqli_error($conn));
                                                    }

                                                    // Bind parameters based on user role
                                                    if ($isSupervisor) {
                                                        mysqli_stmt_bind_param($stmt, "ii", $sessionEmpId, $sessionEmpId);
                                                    } else {
                                                        mysqli_stmt_bind_param($stmt, "i", $sessionEmpId);
                                                    }

                                                    $result = mysqli_stmt_execute($stmt);
                                                    if (!$result) {
                                                        die('Error executing statement: ' . mysqli_stmt_error($stmt));
                                                    }

                                                    // Bind result variables
                                                    mysqli_stmt_bind_result($stmt, $id, $title, $description, $status, $priority, $created_at, $start_date, $due_date, 
                                                                            $assigned_to_first_name, $assigned_to_last_name, $assigned_to_middle_name, $assigned_to_profile,
                                                                            $assigned_by_first_name, $assigned_by_last_name, $assigned_by_middle_name, $assigned_by_profile,
                                                                            $assigned_to_emp_id, $assigned_by_emp_id, $supervisor_id);

                                                    $results = [];
                                                    while (mysqli_stmt_fetch($stmt)) {
                                                        $result = [
                                                            'id' => $id,
                                                            'title' => $title,
                                                            'description' => $description,
                                                            'status' => $status,
                                                            'priority' => $priority,
                                                            'created_at' => $created_at,
                                                            'start_date' => $start_date,
                                                            'due_date' => $due_date,
                                                            'assigned_to' => $assigned_to_first_name . ' ' . $assigned_to_middle_name . ' ' . $assigned_to_last_name,
                                                            'assigned_by' => $assigned_by_first_name . ' ' . $assigned_by_middle_name . ' ' . $assigned_by_last_name,
                                                            'assigned_to_profile' => $assigned_to_profile,
                                                            'assigned_to_emp_id' => $assigned_to_emp_id,
                                                            'assigned_by_emp_id' => $assigned_by_emp_id,
                                                            'supervisor_id' => $supervisor_id,
                                                        ];

                                                        // Calculate time ago (assuming calculate_time_ago() is defined elsewhere)
                                                        $due_label = calculate_time_ago($created_at);
                                                        $result['due_label'] = $due_label;
                                                        $results[] = $result;
                                                    }

                                                    mysqli_stmt_close($stmt);
                                                ?>
                                                <nav class="navbar navbar-light bg-faded m-b-30 p-10">
                                                    <ul class="nav navbar-nav">
                                                        <li class="nav-item active">
                                                            <a class="nav-link" href="#!">Filter: <span class="sr-only">(current)</span></a>
                                                        </li>
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#!" id="bydate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-clock-time"></i> By Date</a>
                                                            <div class="dropdown-menu" aria-labelledby="bydate">
                                                                <?php if (!$timeRange): ?>
                                                                    <a class="dropdown-item active" href="#">Show all</a>
                                                                <?php else: ?>
                                                                    <a class="dropdown-item <?php echo (!$timeRange) ? 'active' : ''; ?>" href="?">Show all</a>
                                                                <?php endif; ?>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item <?php echo $timeRange === 'today' ? 'active' : ''; ?>" href="?timeRange=today">Today</a>
                                                                <a class="dropdown-item <?php echo $timeRange === 'yesterday' ? 'active' : ''; ?>" href="?timeRange=yesterday">Yesterday</a>
                                                                <a class="dropdown-item <?php echo $timeRange === 'this-week' ? 'active' : ''; ?>" href="?timeRange=this-week">This week</a>
                                                                <a class="dropdown-item <?php echo $timeRange === 'this-month' ? 'active' : ''; ?>" href="?timeRange=this-month">This month</a>
                                                                <a class="dropdown-item <?php echo $timeRange === 'this-year' ? 'active' : ''; ?>" href="?timeRange=this-year">This year</a>
                                                            </div>
                                                        </li>
                                                        <!-- end of by date dropdown -->
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#!" id="bystatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-chart-histogram-alt"></i> By Status</a>
                                                            <div class="dropdown-menu" aria-labelledby="bystatus">
                                                                <a class="dropdown-item <?php echo !isset($_GET['status']) ? 'active' : ''; ?>" href="?">Show all</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item <?php echo isset($_GET['status']) && $_GET['status'] === 'Pending' ? 'active' : ''; ?>" href="?status=Pending">Pending</a>
                                                                <a class="dropdown-item <?php echo isset($_GET['status']) && $_GET['status'] === 'In Progress' ? 'active' : ''; ?>" href="?status=In Progress">In Progress</a>
                                                                <a class="dropdown-item <?php echo isset($_GET['status']) && $_GET['status'] === 'Completed' ? 'active' : ''; ?>" href="?status=Completed">Completed</a>
                                                            </div>
                                                        </li>
                                                        <!-- end of by status dropdown -->
                                                    </ul>
                                                </nav>
                                                <!-- Nav Filter tab end -->
                                                <!-- Task board design block start-->
                                                <div class="row">
                                                    <?php foreach ($results as $result){ ?>
                                                        <div class="col-sm-6">
                                                            <?php
                                                            // Assign color class based on priority
                                                            $color_class = '';
                                                            switch ($result['priority']) {
                                                                case 'High':
                                                                    $color_class = 'card-border-warning';
                                                                    break;
                                                                case 'Medium':
                                                                    $color_class = 'card-border-success';
                                                                    break;
                                                                case 'Low':
                                                                    $color_class = 'card-border-primary';
                                                                    break;
                                                                default:
                                                                    $color_class = 'card-border-primary';
                                                            }

                                                            $status = $result['status'];
                                                            $labelClass = '';

                                                            switch ($status) {
                                                                case 'Pending':
                                                                    $labelClass = 'bg-primary';
                                                                    break;
                                                                case 'In Progress':
                                                                    $labelClass = 'badge-info';
                                                                    break;
                                                                case 'Completed':
                                                                    $labelClass = 'badge-success';
                                                                    break;
                                                                default:
                                                                    $labelClass = 'bg-secondary';
                                                                    break;
                                                            }

                                                            $color_btn = '';
                                                            switch ($result['priority']) {
                                                                case 'High':
                                                                    $color_btn = 'btn-warning';
                                                                    break;
                                                                case 'Medium':
                                                                    $color_btn = 'btn-success';
                                                                    break;
                                                                case 'Low':
                                                                    $color_btn = 'btn-primary';
                                                                    break;
                                                                default:
                                                                    $color_btn = 'btn-warning';
                                                            }
                                                            $imagePath = empty($result['assigned_to_profile']) ? '../files/assets/images/user-card/img-round1.jpg' : $result['assigned_to_profile'];
                                                            ?>
                                                            <div class="card <?php echo $color_class; ?>">
                                                                <!-- Job card start -->
                                                                <div>
                                                                    <div class="card-header">
                                                                        <div class="media">
                                                                            <a class="media-left media-middle" href="#">
                                                                                <img class="media-object img-60" src="<?php echo $imagePath; ?>" alt="Generic placeholder image">
                                                                            </a>
                                                                            <div class="media-body media-middle">
                                                                                <div class="company-name">
                                                                                    <?php if ($isSupervisor && $result['assigned_to'] === $_SESSION['sfirstname'] . ' ' . $_SESSION['smiddlename'] . ' ' . $_SESSION['slastname']): ?>
                                                                                        <p>Myself</p>
                                                                                    <?php else: ?>
                                                                                        <p><?php echo $result['assigned_to']; ?></p>
                                                                                    <?php endif; ?>
                                                                                    <span class="text-muted f-14"><?php echo date('d F, Y', strtotime($result['created_at'])); ?></span>
                                                                                </div>
                                                                                <div class="job-badge">
                                                                                    <label class="label <?php echo $labelClass; ?>"><?php echo $status; ?></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-block">
                                                                        <h6 class="job-card-desc">Subject: <?php echo $result['title']; ?></h6>
                                                                        <p class="text-muted">
                                                                             <?php
                                                                                $description = htmlspecialchars_decode($result['description']);
                                                                                $description = strip_tags($description);
                                                                                $description = substr($description, 0, 250);
                                                                                echo $description . (strlen($result['description']) > 250 ? '...' : '');
                                                                            ?>
                                                                        </p>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="job-meta-data me-3" style="margin-right: 40px;">
                                                                                <strong>Start Date:</strong>
                                                                                <label class="label badge-default" style="color: black !important;"><?php echo date('d F, Y', strtotime($result['start_date'])); ?></label>
                                                                            </div>
                                                                            <div class="job-meta-data">
                                                                                <strong>Due Date:</strong>
                                                                                <label class="label badge-default" style="color: black !important;"><?php echo date('d F, Y', strtotime($result['due_date'])); ?></label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-footer">
                                                                            <div class="task-board" style="margin-bottom: 10px;">
                                                                                <div class="dropdown-secondary dropdown">
                                                                                    <button id="priority-dropdown" class="btn <?php echo $color_btn; ?> btn-mini dropdown-toggle waves-effect waves-light" type="button" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        <?php echo $result['priority']; ?>
                                                                                    </button>
                                                                                    <div class="dropdown-menu" aria-labelledby="dropdown1" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                                                        <?php if (($result['supervisor_id'] == $sessionEmpId && $result['assigned_to_emp_id'] != $sessionEmpId)): ?>
                                                                                            <a class="dropdown-priority dropdown-item waves-light waves-effect <?php echo $result['priority'] == 'High' ? 'active' : ''; ?>" href="#!" data-priority="High" data-task-id="<?php echo $result['id']; ?>"><span class="point-marker bg-warning"></span>High priority</a>
                                                                                            <a class="dropdown-priority dropdown-item waves-light waves-effect <?php echo $result['priority'] == 'Medium' ? 'active' : ''; ?>" href="#!" data-priority="Medium" data-task-id="<?php echo $result['id']; ?>"><span class="point-marker bg-success"></span>Medium priority</a>
                                                                                            <a class="dropdown-priority dropdown-item waves-light waves-effect <?php echo $result['priority'] == 'Low' ? 'active' : ''; ?>" href="#!" data-priority="Low" data-task-id="<?php echo $result['id']; ?>"><span class="point-marker bg-info"></span>Low priority</a>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="dropdown-secondary dropdown">
                                                                                    <button id="status-dropdown" class="btn <?php echo $labelClass; ?> btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        <?php echo $result['status'] == "Pending" ? 'Pending' : ($result['status'] == "In Progress" ? 'In Progress' : ($result['status'] == "Completed" ? 'Completed' : 'Pending')); ?>
                                                                                    </button>
                                                                                    <div class="dropdown-menu" aria-labelledby="dropdown2" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                                                        <a class="dropdown-status dropdown-item waves-light waves-effect <?php echo $result['status'] == "Pending" ? 'active' : ''; ?>" href="#!" data-status="Pending" data-task-id="<?php echo $result['id']; ?>">Pending</a>
                                                                                        <a class="dropdown-status dropdown-item waves-light waves-effect <?php echo $result['status'] == "In Progress" ? 'active' : ''; ?>" href="#!" data-status="In Progress" data-task-id="<?php echo $result['id']; ?>">In Progress</a>
                                                                                        <a class="dropdown-status dropdown-item waves-light waves-effect <?php echo $result['status'] == "Completed" ? 'active' : ''; ?>" href="#!" data-status="Completed" data-task-id="<?php echo $result['id']; ?>">Completed</a>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- end of dropdown-secondary -->
                                                                                <div class="dropdown-secondary dropdown">
                                                                                    <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                                                                    <div class="dropdown-menu" aria-labelledby="dropdown3" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                                                        <a class="dropdown-item waves-light waves-effect" href="task_details.php?id=<?php echo $result['id']; ?>&edit=1"><i class="icofont icofont-spinner-alt-5"></i> View Task</a>
                                                                                        <?php if (($result['supervisor_id'] == $sessionEmpId && $result['assigned_to_emp_id'] != $sessionEmpId)): ?>
                                                                                            <div class="dropdown-divider"></div>
                                                                                            <a class="dropdown-item waves-light waves-effect" href="new_task.php?id=<?php echo $result['id']; ?>&edit=1"><i class="icofont icofont-ui-edit"></i> Edit Task</a>
                                                                                            <a class="remove-ticket dropdown-item waves-light waves-effect" href="#!" data-task-id="<?php echo $result['id']; ?>"><i class="icofont icofont-close-line"></i> Remove</a>
                                                                                        <?php endif; ?>    
                                                                                    </div>
                                                                                    <!-- end of dropdown menu -->
                                                                                </div>
                                                                                <!-- end of seconadary -->
                                                                            </div>
                                                                            <!-- end of pull-right class -->
                                                                        </div>
                                                                        <!-- end of card-footer -->
                                                                    </div>
                                                                </div>
                                                                <!-- Job card end -->
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <!-- Task board design block end -->
                                            </div>
                                            <!-- Left column end -->
                                        </div>
                                    </div>
                                    <!-- Page body end -->
                                </div>
                            </div>
                            <!-- Main-body end -->

                            <div id="styleSelector">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Jquery -->
    <?php
        // Query database for count of each priority level
       $query = "SELECT
            SUM(CASE WHEN priority = 'High' THEN 1 ELSE 0 END) AS high_count,
            SUM(CASE WHEN priority = 'Medium' THEN 1 ELSE 0 END) AS normal_count,
            SUM(CASE WHEN priority = 'Low' THEN 1 ELSE 0 END) AS low_count
          FROM tbltask";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        $high_count = $row['high_count'];
        $normal_count = $row['normal_count'];
        $low_count = $row['low_count'];

        $total_count = $high_count + $normal_count + $low_count;

        // Calculate percentage width for each loader bar
        $high_width = ($total_count != 0) ? ($high_count / $total_count) * 100 : 0;
        $normal_width = ($total_count != 0) ? ($normal_count / $total_count) * 100 : 0;
        $low_width = ($total_count != 0) ? ($low_count / $total_count) * 100 : 0;
    ?>
    
    <?php include('../includes/scripts.php')?>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
    <script>
        setTimeout(function() {
            // Set width of each loader bar
            var high_progress = document.querySelector('.faq-bar-high');
            high_progress.style.width = '<?php echo $high_width; ?>%';

            var normal_progress = document.querySelector('.faq-bar-normal');
            normal_progress.style.width = '<?php echo $normal_width; ?>%';

            var low_progress = document.querySelector('.faq-bar-low');
            low_progress.style.width = '<?php echo $low_width; ?>%';
        }, 1000); // 1000ms = 1 second delay
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.dropdown-priority', function(event) {
                console.log('Dropdown item clicked');
                event.preventDefault();
                (async () => {
                    const { value: formValues } = await Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to update this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, update it!'
                    });

                    var selectedPriority = $(this).data('priority');
                    $('#priority-dropdown').html(selectedPriority);
                    var taskId = $(this).data('task-id');

                    console.log('taskId:', taskId); 

                    if (formValues) {
                        var data = {
                            id: taskId,
                            priority: selectedPriority,
                            action: "update-task-priority"
                        };

                        console.log('Data HERE: ' + JSON.stringify(data));
                        $.ajax({
                            url: '../admin/task_functions.php',
                            type: 'post',
                            data: data,
                            success: function(response) {
                                const responseObject = JSON.parse(response);
                                console.log(`RESPONSE: ${response}`);
                                console.log(`RESPONSE HERE: ${responseObject}`);
                                console.log(`RESPONSE HERE: ${responseObject.message}`);
                                if (response && responseObject.status === 'success') {
                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        html: responseObject.message,
                                        confirmButtonColor: '#01a9ac',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                    
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        text: responseObject.message,
                                        confirmButtonColor: '#eb3422',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log("AJAX error: " + error);
                                console.log('Data HERE: ' + JSON.stringify(data));
                                Swal.fire('Error!', 'Failed to update priority.', 'error');
                            }

                        });
                    }
                    
                })()
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.dropdown-status', function(event) {
                console.log('Dropdown item clicked');
                event.preventDefault();
                (async () => {
                    const { value: formValues } = await Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to update this status!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, update it!'
                    });

                    var selectedStatus = $(this).data('status');
                    $('#status-dropdown').html(selectedStatus);
                    var taskId = $(this).data('task-id');

                    console.log('taskId:', taskId); 

                    if (formValues) {
                        var data = {
                            id: taskId,
                            status: selectedStatus,
                            action: "update-task-status"
                        };

                        console.log('Data HERE: ' + JSON.stringify(data));
                        $.ajax({
                            url: '../admin/task_functions.php',
                            type: 'post',
                            data: data,
                            success: function(response) {
                                const responseObject = JSON.parse(response);
                                console.log(`RESPONSE: ${response}`);
                                console.log(`RESPONSE HERE: ${responseObject}`);
                                console.log(`RESPONSE HERE: ${responseObject.message}`);
                                if (response && responseObject.status === 'success') {
                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        html: responseObject.message,
                                        confirmButtonColor: '#01a9ac',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                    
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        text: responseObject.message,
                                        confirmButtonColor: '#eb3422',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log("AJAX error: " + error);
                                console.log('Data HERE: ' + JSON.stringify(data));
                                Swal.fire('Error!', 'Failed to update status.', 'error');
                            }

                        });
                    }
                    
                })()
            });
        });
    </script>

    <script type="text/javascript">
        $('.remove-task').click(function(){
            (async () => {
                const { value: formValues } = await Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                
                var taskId = $(this).data('task-id');

                console.log('taskId:', taskId); 

                if (formValues) {
                var data = {
                    id: taskId,
                    action: "remove-task"
                };
                console.log('Data HERE: ' + JSON.stringify(data));
                $.ajax({
                    url: '../admin/task_functions.php',
                    type: 'post',
                    data: data,
                    success: function(response) {
                        const responseObject = JSON.parse(response);
                        console.log(`RESPONSE: ${response}`);
                        console.log(`RESPONSE HERE: ${responseObject}`);
                        console.log(`RESPONSE HERE: ${responseObject.message}`);
                        if (response && responseObject.status === 'success') {
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                html: responseObject.message,
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                            
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: responseObject.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX error: " + error);
                        console.log('Data HERE: ' + JSON.stringify(data));
                        Swal.fire('Error!', 'Failed to delete task.', 'error');
                    }

                });
            }
            })()
        })
    </script>

</body>

</html>
