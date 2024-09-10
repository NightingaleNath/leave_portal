<?php include('../includes/header.php') ?>
<?php include('../includes/utils.php') ?>

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

// Check if the department filter is set
$leaveStatusFilter = isset($_GET['leave_status']) ? $_GET['leave_status'] : 'Show all';

// Initialize variables for the filter dropdown
$selectedLeaveStatus = null;
$selectedLeaveStatusName = 'Show all';

if ($leaveStatusFilter !== 'Show all') {
    switch ($leaveStatusFilter) {
        case '0':
            $selectedLeaveStatusName = 'Pending';
            break;
        case '1':
            $selectedLeaveStatusName = 'Approved';
            break;
        case '2':
            $selectedLeaveStatusName = 'Cancelled';
            break;
        case '3':
            $selectedLeaveStatusName = 'Recalled';
            break;
        case '4':
            $selectedLeaveStatusName = 'Rejected';
            break;
    }
}

// Get the current user's role and department from session
$userRole = $_SESSION['srole'];
$userId = $_SESSION['slogin'];
$userDepartment = $_SESSION['department'];
$isSupervisor = $_SESSION['is_supervisor'];

// Construct the query with conditions based on user role
$query = "
    SELECT l.id, l.leave_type_id, l.requested_days, l.from_date, l.to_date, l.remarks, l.created_date, l.reviewed_by, l.reviewed_date, l.leave_status, l.empid, e.first_name, e.middle_name, e.last_name 
    FROM tblleave l
    JOIN tblemployees e ON l.empid = e.emp_id
    JOIN tblleavetype lt ON l.leave_type_id = lt.id
";

$conditions = [];

if ($userRole !== 'Admin') {
    if ($userRole === 'Manager') {
        $conditions[] = "e.department = '$userDepartment' AND l.empid != $userId";
    } elseif ($isSupervisor == 1) {
        $conditions[] = "e.supervisor_id = $userId AND l.empid != $userId";
    }
}

if ($leaveStatusFilter !== 'Show all') {
    $conditions[] = "l.leave_status = ?";
    $selectedLeaveStatus = $leaveStatusFilter; // Update the selected leave status
}

// Add conditions to the query
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Prepare the statement
$stmt = mysqli_prepare($conn, $query);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
}

if ($leaveStatusFilter !== 'Show all') {
    // Bind the leave status filter parameter if it's set
    mysqli_stmt_bind_param($stmt, "i", $selectedLeaveStatus);
}

// Execute the statement
if (!mysqli_stmt_execute($stmt)) {
    die('Execute failed: ' . htmlspecialchars(mysqli_stmt_error($stmt)));
}

mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $id, $leave_type_id, $requested_days, $from_date, $to_date, $remarks, $created_date, $reviewed_by, $reviewed_date, $leave_status, $empid, $first_name, $middle_name, $last_name);

$leaveData = [];

while (mysqli_stmt_fetch($stmt)) {
    $leaveData[] = [
        'id' => $id,
        'leave_type_id' => $leave_type_id,
        'requested_days' => $requested_days,
        'from_date' => $from_date,
        'to_date' => $to_date,
        'remarks' => $remarks,
        'created_date' => $created_date,
        'reviewed_by' => $reviewed_by,
        'reviewed_date' => $reviewed_date,
        'leave_status' => $leave_status,
        'empid' => $empid,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'last_name' => $last_name
    ];
}

mysqli_stmt_close($stmt);

$leaveStatusMap = [
    0 => 'Pending',
    1 => 'Approved',
    2 => 'Cancelled',
    3 => 'Recalled',
    4 => 'Rejected'
];

// Count the total leave requests for each status
$leaveStatusCounts = array_fill_keys(array_keys($leaveStatusMap), 0);
foreach ($leaveData as $leave) {
    $leaveStatus = $leave['leave_status'];
    if (isset($leaveStatusCounts[$leaveStatus])) {
        $leaveStatusCounts[$leaveStatus]++;
    } else {
        // Handle unknown leave status
        $leaveStatusCounts['Unknown']++;
    }
}
?>

<body>
    <!-- Pre-loader start -->
    <?php include('../includes/loader.php') ?>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

            <?php include('../includes/topbar.php') ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php $page_name = "supervisee_leave_request"; ?>
                    <?php include('../includes/sidebar.php') ?>
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
                                                        <h4>Leave Portal - Supervisee Leave Request</h4>
                                                        <span>Verify and respond to the leave requests</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Page-header end -->
                                    <!-- Page body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-lg-12 filter-bar">
                                                <!-- Nav Filter tab start -->
                                                <nav class="navbar navbar-light bg-faded m-b-30 p-10">
                                                    <ul class="nav navbar-nav">
                                                        <li class="nav-item active">
                                                            <a class="nav-link" href="#!">Filter By Status: <span class="sr-only">(current)</span></a>
                                                        </li>
                                                        <!-- Your existing HTML for the dropdown -->
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#!" id="bystatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="icofont icofont-home"></i> <?php echo $selectedLeaveStatusName; ?>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="bystatus">
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Show all') ? 'active' : ''; ?>" href="?leave_status=Show all">Show all</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Pending') ? 'active' : ''; ?>" href="?leave_status=0">Pending</a>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Approved') ? 'active' : ''; ?>" href="?leave_status=1">Approved</a>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Cancelled') ? 'active' : ''; ?>" href="?leave_status=2">Cancelled</a>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Recalled') ? 'active' : ''; ?>" href="?leave_status=3">Recalled</a>
                                                                <a class="dropdown-item <?php echo ($selectedLeaveStatusName === 'Rejected') ? 'active' : ''; ?>" href="?leave_status=4">Rejected</a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div class="nav-item nav-grid">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="searchInput" placeholder="Search here...">
                                                            <span class="input-group-addon" id="basic-addon1"><i class="icofont icofont-search"></i></span>
                                                        </div>
                                                    </div>
                                                    <!-- end of by priority dropdown -->
                                                </nav>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- Left column start -->
                                            <div id="leaveMain" class="col-lg-9">
                                                <div id="leaveContainer" class="job-card card-columns">
                                                    <!-- Populate it from ../admin/leave_functions.php -->
                                                </div>
                                            </div>
                                            <!-- Left column end -->
                                            <!-- Right column start -->
                                            <div id="leaveInformation" class="col-lg-3">
                                                <!-- Leave Status card start -->
                                                <div class="card job-right-header">
                                                    <div class="card-header">
                                                        <h5>Leave Status Information</h5>
                                                    </div>
                                                    <div class="card-block">
                                                        <form action="#">
                                                            <?php
                                                            foreach ($leaveStatusCounts as $status => $count) {
                                                                if (isset($leaveStatusMap[$status])) {
                                                                    $leaveStatus = $leaveStatusMap[$status];
                                                                } else {
                                                                    $leaveStatus = 'Unknown';
                                                                }
                                                                echo '<div class="checkbox-fade fade-in-primary">
                                                                                <label>
                                                                                    <input type="checkbox" value="" checked="checked" disabled>
                                                                                    <span class="cr">
                                                                                        <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                    </span>
                                                                                </label>
                                                                                <div> <a href="supervisee_leave_request.php?leave_status=' . $status . '">' . $leaveStatus . ' <span class="text-muted">(' . $count . ')</span> <a/></div>
                                                                            </div>';
                                                            }
                                                            ?>

                                                        </form>
                                                    </div>

                                                </div>
                                                <!-- Leave Status card end -->
                                            </div>
                                        </div>
                                        <!-- Right column end -->

                                        <!-- confirm mail start -->
                                        <div id="confirm-mail" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="login-card card-block login-card-modal">
                                                    <form class="md-float-material">
                                                        <div class="card m-t-15">
                                                            <div class="auth-box card-block">
                                                                <div class="row m-b-20">
                                                                    <div class="col-md-12 confirm">
                                                                        <h3 class="text-center txt-primary"><i class="icofont icofont-check-circled text-primary"></i> Leave Review</h3>
                                                                    </div>
                                                                </div>
                                                                <input hidden type="text" class="form-control leave-id" name="leave-id">
                                                                <p class="text-inverse text-left m-t-15 f-16"><b>Dear <span id="modalReviewer"></span></b>, </p>
                                                                <p id="modalMessage" class="text-inverse text-left m-b-20"></p>
                                                                <ul class="text-inverse text-left m-b-30">
                                                                    <li><strong>Leave Type: </strong> <span id="modalLeaveType"></span></li>
                                                                    <li><strong>Requested Days: </strong> <span id="modalRequestedDays"></span></li>
                                                                    <li><strong>Remaining Days: </strong> <span id="modalRemaing"></span></li>
                                                                    <li><strong>Leave Current Status: </strong> <span id="modalLeaveStatus"></span></li>
                                                                </ul>
                                                                <div class="card-block">
                                                                    <div class="row" id="radioButtonsContainer">
                                                                        <!-- options will be dynamically inserted here -->
                                                                    </div>
                                                                    <!-- <span class="input-group-addon" id="basic-addon1"><i class="icofont icofont-verification-check"></i></span>
                                                                <input type="text" class="form-control" value="https://www.website.com/signup/verify?id657&amp;key=7364fr5"> -->
                                                                </div>
                                                                <div id="loader-wrapper" style="display: none;">
                                                                    <div class="preloader3 loader-block" style="height: 50px;">
                                                                        <div class="circ1"></div>
                                                                        <div class="circ2"></div>
                                                                        <div class="circ3"></div>
                                                                        <div class="circ4"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row m-t-15">
                                                                    <div class="col-md-12">
                                                                        <button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center">Update</button>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <p class="text-inverse text-left m-b-0 m-t-10"></p>
                                                                        <p class="text-inverse text-left"><b></b></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- end of form -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Confirm mail end-->

                                    </div>
                                </div>
                                <!-- Page body start -->
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


    <!-- Required Jquery -->
    <?php include('../includes/scripts.php') ?>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.status-update', function(event) {
                console.log('Button item clicked');
                event.preventDefault();

                $('.status-update').prop('disabled', true);
                $('#loader-wrapper').fadeIn();
                $('.modal').css('z-index', '1050');

                (async () => {
                    const {
                        value: formValues
                    } = await Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to update this status!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, update it!'
                    });

                    var selectedStatus = $('#select').val();
                    const leaveId = $('.leave-id').val();

                    console.log('leaveId:', leaveId);
                    console.log('selectedStatus:', selectedStatus);

                    if (formValues) {
                        var data = {
                            id: leaveId,
                            status: selectedStatus,
                            action: "update-leave-status"
                        };

                        console.log('Data HERE: ' + JSON.stringify(data));
                        $.ajax({
                            url: '../admin/leave_functions.php',
                            type: 'post',
                            data: data,
                            success: function(response) {
                                $('.status-update').prop('disabled', false);
                                $('#loader-wrapper').fadeOut();
                                console.log(`RESPONSE HERE: ${response}`);
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
                                $('.status-update').prop('disabled', false);
                                $('#loader-wrapper').fadeOut();
                                console.log("AJAX error: " + error);
                                console.log('Data HERE: ' + JSON.stringify(data));
                                Swal.fire('Error!', 'Failed to delete department.', 'error');
                            }

                        });
                    }

                })()
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Retrieve the initial department filter value
            var selectedStatus = '<?php echo $selectedLeaveStatusName; ?>';
            // Function to fetch and display the filtered staff
            console.log('RESPONSE HERE: ' + selectedStatus);

            function fetchStaff() {
                var searchQuery = $('#searchInput').val(); // Get the search query
                var leaveStatusFilter = (selectedStatus === 'Show all') ? '' : selectedStatus; // Get the selectedStatus filter value
                // Make an AJAX request to fetch the filtered staff
                $.ajax({
                    url: '../admin/leave_functions.php', // Replace with the actual PHP script that fetches the staff from the database
                    type: 'POST',
                    data: {
                        searchQuery: searchQuery,
                        leaveStatusFilter: leaveStatusFilter
                    },

                    success: function(response) {
                        // Clear the existing staff cards
                        $('#leaveContainer').empty();
                        $('#leaveInformation').show();

                        console.log('RESPONSE HERE: ' + response);

                        // Append the fetched staff cards to the container
                        if (response.includes('files/assets/images/no_data.png')) {
                            console.log('No data image found in the response.');

                            // Set the class of the id leaveMain to be col-sm-12
                            $('#leaveMain').removeClass().addClass('col-sm-12');
                            $('#leaveInformation').hide();

                            // Remove the class of the id leaveContainer
                            $('#leaveContainer').removeClass();

                            // Append the fetched staff cards to the container
                            $('#leaveContainer').append(response);
                        } else {
                            // Maintain the current setup and append the response
                            $('#leaveContainer').append(response);
                        }
                    }
                });
            }
            // Event listener for search input field
            $('#searchInput').on('keyup', function() {
                fetchStaff();
            });

            // Event listener for department filter dropdown
            $('#bystatus .dropdown-item').on('click', function(event) {
                event.preventDefault();
                // Update the selected department variable and dropdown text
                selectedStatus = $(this).text().trim();
                $('#bystatus').text(selectedStatus);

                // Fetch the staff based on the updated filter
                fetchStaff();
            });

            // Fetch the initial staff based on the default filter
            fetchStaff();
        });

        $(document).ready(function() {
            // Function to format dates as "6th May, 2024"
            function formatDate(date) {
                var day = date.getDate();
                var month = date.toLocaleString('default', {
                    month: 'long'
                });
                var year = date.getFullYear();

                // Determine the ordinal suffix
                var suffix = 'th';
                if (day % 10 === 1 && day !== 11) {
                    suffix = 'st';
                } else if (day % 10 === 2 && day !== 12) {
                    suffix = 'nd';
                } else if (day % 10 === 3 && day !== 13) {
                    suffix = 'rd';
                }

                return day + suffix + ' ' + month + ', ' + year;
            }

            // Handle the click event for the "Review" link
            $(document).on('click', '.review-btn', function() {
                // Get the data attributes from the clicked link
                var leaveType = $(this).data('leave-type');
                var reason = $(this).data('leave-reason');
                var remaing = $(this).data('leave-remaing');
                var requestedDays = $(this).data('requested-days');
                var staff = $(this).data('leave-staff');
                var leaveStatus = $(this).data('leave-status');
                var leaveId = $(this).data('leave-id');
                var startDate = new Date($(this).data('start-date'));
                var endDate = new Date($(this).data('expiry-date'));
                var submissionDate = new Date($(this).data('submission-date'));
                var reviewer = '<?php echo ($session_sfirstname ? $session_sfirstname : '') . " " . ($session_smiddlename ? $session_smiddlename : '') . " " . ($session_slastname ? $session_slastname : ''); ?>';

                // Map leave status strings to numeric values
                var statusMap = {
                    "Pending": 0,
                    "Approved": 1,
                    "Cancelled": 2,
                    "Recalled": 3,
                    "Rejected": 4
                };

                // Convert the string leave status to its corresponding numeric value
                var leaveStatusValue = statusMap[leaveStatus];

                // Populate the modal with the data
                $('#modalLeaveType').text(leaveType);
                $('#modalRequester').text(staff);
                $('#modalReviewer').text(reviewer);
                $('#modalRequestedDays').text(requestedDays);
                $('#modalRemaing').text(remaing);
                $('#modalLeaveStatus').text(leaveStatus);
                $('#modalLeaveId').text(leaveId);

                $('.leave-id').val(leaveId);

                // Clear previous radio buttons
                $('#radioButtonsContainer').empty();

                var today = new Date();

                console.log("COMPARE: " + (today < startDate) + startDate + today);
                console.log("COMPARE: " + endDate);

                var formattedSubmissionDate = formatDate(submissionDate);
                var formattedStartDate = formatDate(startDate);
                var formattedEndDate = formatDate(endDate);

                switch (leaveStatus) {
                    case "Pending":
                        $('#modalLeaveStatus').addClass('text-primary');
                        break;
                    case "Approved":
                        $('#modalLeaveStatus').addClass('text-success');
                        break;
                    case "Cancelled":
                        $('#modalLeaveStatus').addClass('text-warning');
                        break;
                    case "Recalled":
                        $('#modalLeaveStatus').addClass('text-info');
                        break;
                    case "Rejected":
                        $('#modalLeaveStatus').addClass('text-danger');
                        break;
                    default:
                        // Default color or handling if status is not recognized
                        break;
                }

                var modalMessage;
                switch (leaveStatusValue) {
                    case 0: // Pending
                        if (today > endDate) {
                            modalMessage = "The leave request submitted by <b>" + staff + "</b> on <b>" + formattedSubmissionDate + "</b> for the period from <b>" + formattedStartDate + "</b> to <b>" + formattedEndDate + "</b> is pending, but the requested leave period has already passed. It is too late to approve or reject this request.";
                        } else {
                            modalMessage = "You are about to review the pending leave request submitted by <b>" + staff + "</b> on <b>" + formattedSubmissionDate + "</b> for the period from <b>" + formattedStartDate + "</b> to <b>" + formattedEndDate + "</b>. Please review the details carefully and decide whether to approve or reject the request.";
                        }
                        break;
                    case 1: // Approved
                        if (today < startDate) {
                            modalMessage = "The leave request submitted by <b>" + staff + "</b> on <b>" + formattedSubmissionDate + "</b> for the period from <b>" + formattedStartDate + "</b> to <b>" + formattedEndDate + "</b> has been approved. You can choose to recall the approval if needed.";
                        } else if (today >= startDate && today <= endDate) {
                            modalMessage = "The leave request submitted by <b>" + staff + "</b> on <b>" + formattedSubmissionDate + "</b> for the period from <b>" + formattedStartDate + "</b> to <b>" + formattedEndDate + "</b> is currently in progress.";
                        } else {
                            modalMessage = "The leave request submitted by <b>" + staff + "</b> on <b>" + formattedSubmissionDate + "</b> for the period from <b>" + formattedStartDate + "</b> to <b>" + formattedEndDate + "</b> has been completed.";
                        }
                        break;
                    case 2: // Cancelled
                        modalMessage = "The leave request submitted by <b>" + staff + "</b> on <b>" + formattedSubmissionDate + "</b> for the period from <b>" + formattedStartDate + "</b> to <b>" + formattedEndDate + "</b> has been cancelled.";
                        break;
                    case 3: // Recalled
                        modalMessage = "The approved leave request submitted by <b>" + staff + "</b> on <b>" + formattedSubmissionDate + "</b> for the period from <b>" + formattedStartDate + "</b> to <b>" + formattedEndDate + "</b> has been recalled.";
                        break;
                    case 4: // Rejected
                        modalMessage = "The leave request submitted by <b>" + staff + "</b> on <b>" + formattedSubmissionDate + "</b> for the period from <b>" + formattedStartDate + "</b> to <b>" + formattedEndDate + "</b> has been rejected.";
                        break;
                    default:
                        modalMessage = "You are about to review the leave request submitted by <b>" + staff + "</b> on <b>" + formattedSubmissionDate + "</b> for the period from <b>" + formattedStartDate + "</b> to <b>" + formattedEndDate + "</b>. Please review the details carefully and decide whether to approve or reject the request.";
                }
                $('#modalMessage').html(modalMessage);

                // Determine if options should be shown based on leave status and dates
                if (leaveStatusValue === 0) { // Pending
                    if (today <= endDate) {
                        $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary">
                            <option value="0" selected>Pending</option>
                            <option value="1">Approved</option>
                            <option value="2">Cancelled</option>
                            <option value="4">Rejected</option>
                        </select>
                    `);
                    } else {
                        $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary" disabled>
                            <option value="0" selected>Pending</option>
                        </select>
                    `);
                    }
                } else if (leaveStatusValue === 1) { // Approved
                    if (today < startDate || (today >= startDate && today <= endDate)) {
                        $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary">
                            <option value="1" selected>Approved</option>
                            <option value="3">Recalled</option>
                        </select>
                    `);
                    } else {
                        $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary" disabled>
                            <option value="1" selected>Approved</option>
                        </select>
                    `);
                    }
                } else if (leaveStatusValue === 2) { // Cancelled
                    if (today < startDate) {
                        $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary">
                            <option value="2" selected>Cancelled</option>
                            <option value="0">Pending</option>
                        </select>
                    `);
                    } else {
                        $('#radioButtonsContainer').append(`
                        <select name="select" id="select" class="form-control form-control-primary" disabled>
                           <option value="2" selected>Cancelled</option>
                        </select>
                    `);
                    }
                }
                // No options for Rejected (4) or Recalled (3)
                else {
                    $('#radioButtonsContainer').append(`
                    <select name="select" id="select" class="form-control form-control-primary" disabled>
                        <option value="${leaveStatusValue}" selected>${leaveStatus}</option>
                    </select>
                `);
                }

                // Update the button based on the status and date
                var updateButtonHTML;
                if (leaveStatusValue === 0) { // Pending
                    if (today > endDate) {
                        updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>This request was <b style="color: #eb3422;"> PASSED </b></button>';
                    } else {
                        updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Update</button>';
                    }
                } else if (leaveStatusValue === 1) { // Approved
                    if (today >= startDate && today <= endDate) {
                        updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Update</button>';
                    } else if (today < startDate) {
                        updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Update</button>';
                    } else {
                        updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>This request has <b style="color: #eb3422;"> EXPIRED </b></button>';
                    }
                } else if (leaveStatusValue === 2) { // Cancelled
                    if (today < startDate) {
                        updateButtonHTML = '<button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center status-update">Update</button>';
                    } else {
                        updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>This request was <b style="color: #eb3422;"> CANCELLED </b></button>';
                    }
                } else if (leaveStatusValue === 4) { // Rejected
                    updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>This request was <b style="color: #eb3422;"> REJECTED </b></button>';
                } else if (leaveStatusValue === 3) { // Recalled
                    updateButtonHTML = '<button type="button" class="btn btn-disabled btn-md btn-block waves-effect text-center status-update" disabled>This request was <b style="color: #eb3422;"> RECALLED </b></button>';
                }

                // Update the button in the modal
                $('.row.m-t-15 .col-md-12').html(updateButtonHTML);

                function performInitialCheck() {
                    var stat = $('#select').val();
                    console.log("COMPARE: " + stat);
                    // Compare leaveStatusValue with the selected option value (stat)
                    if (leaveStatusValue == stat) {
                        // If they are the same, disable the update button
                        $('.status-update').prop('disabled', true).removeClass('btn-primary').addClass('btn-disabled');
                    } else {
                        // If they are different, enable the update button
                        $('.status-update').prop('disabled', false).removeClass('btn-disabled').addClass('btn-primary');
                    }
                }

                // Set the initial value of the select element based on data from the database
                $('#select').val(leaveStatusValue);

                // Perform the initial check
                performInitialCheck();

                // Attach change event handler to perform the check whenever the select value changes
                $('#select').change(performInitialCheck);

            });
        });
    </script>

</body>

</html>