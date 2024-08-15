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

// Check if the department filter is set
$departmentFilter = isset($_GET['department']) ? $_GET['department'] : 'Show all';

// Initialize variables for the filter dropdown
$selectedDepartmentId = null;
$selectedDepartmentName = 'Show all';

// Construct the query
$query = "SELECT emp_id, first_name, middle_name, last_name, phone_number, designation, email_id, department, image_path FROM tblemployees";

// Check if a specific department is selected
if ($departmentFilter !== 'Show all') {
    // Perform a lookup to get the department ID based on the selected department name
    $departmentLookupQuery = mysqli_prepare($conn, "SELECT id FROM tbldepartments WHERE department_name = ?");
    mysqli_stmt_bind_param($departmentLookupQuery, "s", $departmentFilter);
    mysqli_stmt_execute($departmentLookupQuery);
    mysqli_stmt_store_result($departmentLookupQuery);
    mysqli_stmt_bind_result($departmentLookupQuery, $selectedDepartmentId);

    // Fetch the department ID
    mysqli_stmt_fetch($departmentLookupQuery);
    mysqli_stmt_close($departmentLookupQuery);

    // Modify the query to filter by department ID
    $query .= " WHERE department = ?";
    $selectedDepartmentName = $departmentFilter; // Update the selected department name
}

$query .= " ORDER BY date_created DESC";

// Prepare and execute the query
$stmt = mysqli_prepare($conn, $query);

if ($departmentFilter !== 'Show all') {
    // Bind the department filter parameter
    mysqli_stmt_bind_param($stmt, "s", $selectedDepartmentId);
}

mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $id, $firstname, $middlename, $lastname, $contact, $designation, $email, $department, $image_path);

$employeeData = [];

while (mysqli_stmt_fetch($stmt)) {
    $employeeData[] = [
        'id' => $id,
        'firstname' => $firstname,
        'middlename' => $middlename,
        'lastname' => $lastname,
        'contact' => $contact,
        'designation' => $designation,
        'email' => $email,
        'department' => $department,
        'image_path' => $image_path,
    ];
}

mysqli_stmt_close($stmt);
?>

<body>
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
                                                    <h4>Staff List</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-header end -->
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            
                                            <div class="col-lg-12 filter-bar">
                                                <!-- Nav Filter tab start -->
                                                <nav class="navbar navbar-light bg-faded m-b-30 p-10">
                                                    <ul class="nav navbar-nav">
                                                        <li class="nav-item active">
                                                            <a class="nav-link" href="#!">Filter By Department: <span class="sr-only">(current)</span></a>
                                                        </li>
                                                        <!-- Your existing HTML for the dropdown -->
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#!" id="bydepartment" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="icofont icofont-home"></i> <?php echo $departmentFilter; ?>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="bydepartment">
                                                                <a class="dropdown-item <?php echo ($selectedDepartmentName === 'Show all') ? 'active' : ''; ?>" href="?department=Show all">Show all</a>
                                                                <div class="dropdown-divider"></div>
                                                                <?php
                                                                $departmentLookup = [];
                                                                $departmentQuery = mysqli_query($conn, "SELECT id, department_name FROM tbldepartments");
                                                                if ($departmentQuery && mysqli_num_rows($departmentQuery) > 0) {
                                                                    while ($row = mysqli_fetch_assoc($departmentQuery)) {
                                                                        $departmentId = $row['id'];
                                                                        $departmentName = $row['department_name'];
                                                                        $departmentLookup[$departmentId] = $departmentName;
                                                                    }
                                                                }

                                                                foreach ($departmentLookup as $id => $name) {
                                                                    $isActive = ($selectedDepartmentName === $name) ? 'active' : '';
                                                                    echo '<a class="dropdown-item ' . $isActive . '" href="?department=' . $name . '">' . $name . '</a>';
                                                                }
                                                                ?>
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
                                        <!-- rounded staff card start -->
                                        <div id="staffContainer" class="row users-card">
                                        </div>
                                        <!-- Rounded staff card end -->
                                    </div>
                                    <!-- Page-body end -->
                                </div>
                            </div>
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
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>

    <script type="text/javascript">
    $(document).ready(function() {
        // Retrieve the initial department filter value
        var selectedDepartment = '<?php echo $selectedDepartmentName; ?>';
        // Function to fetch and display the filtered staff
        function fetchStaff() {
            var searchQuery = $('#searchInput').val(); // Get the search query
            var departmentFilter = (selectedDepartment === 'Show all') ? '' : selectedDepartment; // Get the department filter value
            // Make an AJAX request to fetch the filtered staff
            $.ajax({
                url: 'staff_list_functions.php', // Replace with the actual PHP script that fetches the staff from the database
                type: 'POST',
                data: { searchQuery: searchQuery, departmentFilter: departmentFilter },
                success: function(response) {
                    // Clear the existing staff cards
                    $('#staffContainer').empty();

                    // Append the fetched staff cards to the container
                    $('#staffContainer').append(response);
                }
            });
        }
        // Event listener for search input field
        $('#searchInput').on('keyup', function() {
            fetchStaff();
        });

        // Event listener for department filter dropdown
        $('#bydepartment .dropdown-item').on('click', function(event) {
            event.preventDefault();
            // Update the selected department variable and dropdown text
            selectedDepartment = $(this).text().trim();
            $('#bydepartment').text(selectedDepartment);

            // Fetch the staff based on the updated filter
            fetchStaff();
        });

        // Fetch the initial staff based on the default filter
        fetchStaff();
    });
    </script>

</body>

</html>
