<?php include('../includes/header.php')?>
<?php
// Check if the user is logged in
if (!isset($_SESSION['slogin']) || !isset($_SESSION['srole'])) {
    header('Location: ../index.php');
    exit();
}

// Check if the user has the role of Manager or Admin
$userRole = $_SESSION['srole'];
if ($userRole !== 'Staff' && $_SESSION['is_supervisor'] !== 1) {
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
                <?php $page_name = "new_task"; ?>
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
                                                    <h4>Assigned Task</h4>
                                                 </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <!-- Page-header end -->
                                   
                                <!-- Page body start -->
                                <div class="page-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <!-- Basic Inputs Validation start -->
                                            <?php
                                                // Check if the edit parameter is set and fetch the record from the database
                                                if(isset($_GET['edit']) && $_GET['edit'] == 1 && isset($_GET['id'])) {
                                                    $id = $_GET['id'];
                                                    $stmt = mysqli_prepare($conn, "SELECT * FROM tbltask WHERE id = ?");
                                                    mysqli_stmt_bind_param($stmt, "i", $id);
                                                    mysqli_stmt_execute($stmt);
                                                    $result = mysqli_stmt_get_result($stmt);
                                                    $row = mysqli_fetch_assoc($result);
                                                }
                                            ?>
                                            <div class="card">
                                                <div class="card-block">
                                                    <div class="row">
                                                        <div class="col-sm-6 mobile-inputs">
                                                            <h4 class="sub-title"></h4>
                                                            <form>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block">Title</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <input type="text" id="title" name="title"autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['title']) ? $row['title'] : ''; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <label for="userName-2" class="block">Assign To</label>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <select class="js-example-disabled-results col-sm-12" name="assigned_to" id="assigned_to" required>
                                                                            <?php
                                                                            // Check if we are editing a record and have a pre-selected employee
                                                                            if (!empty($row['assigned_to'])) {
                                                                                // Query the database to get the employee details
                                                                                $stmt = mysqli_prepare($conn, "SELECT emp_id, first_name, middle_name, last_name, designation FROM tblemployees WHERE emp_id = ?");
                                                                                mysqli_stmt_bind_param($stmt, "i", $row['assigned_to']);
                                                                                mysqli_stmt_execute($stmt);
                                                                                mysqli_stmt_bind_result($stmt, $emp_id, $first_name, $middle_name, $last_name, $designation);
                                                                                mysqli_stmt_fetch($stmt);
                                                                                mysqli_stmt_close($stmt);

                                                                                // Output the selected option
                                                                                $selected_employee_name = $first_name . ' ' . $middle_name . ' ' . $last_name;
                                                                                //echo '<option value="' . $emp_id . '" selected>' . $selected_employee_name . '</option>';
                                                                                echo '<option value="' . $emp_id . '">' . $selected_employee_name . ' (' . $designation . ')</option>';

                                                                                // Prepare the base query to fetch other employees
                                                                                $query = "SELECT emp_id, first_name, middle_name, last_name, designation, department, role, is_supervisor 
                                                                                        FROM tblemployees WHERE 1=1";

                                                                                // Adjust the query based on the role of the logged-in user
                                                                                if ($session_role == 'Admin') {
                                                                                    $query .= " AND can_be_assigned = 'YES'";
                                                                                } elseif ($session_role == 'Manager') {
                                                                                    $query .= " AND department = ? AND role != 'Admin'";
                                                                                } elseif ($session_role == 'Staff' && $session_supervisor == 1) {
                                                                                    $query .= " AND supervisor_id = ?";
                                                                                }

                                                                                // Execute the adjusted query
                                                                                $stmt = $conn->prepare($query);

                                                                                // Bind parameters based on the role
                                                                                if ($session_role == 'Manager') {
                                                                                    $stmt->bind_param("s", $session_depart);
                                                                                } elseif ($session_role == 'Staff' && $session_supervisor == 1) {
                                                                                    $stmt->bind_param("i", $session_id);
                                                                                }

                                                                                $stmt->execute();
                                                                                $stmt->bind_result($emp_id, $first_name, $middle_name, $last_name, $designation, $department, $role, $is_supervisor);

                                                                                // Fetch and populate the dropdown with other options
                                                                                while ($stmt->fetch()) {
                                                                                    // Skip the selected employee
                                                                                    if ($emp_id != $row['assigned_to']) {
                                                                                        $employee_name = $first_name . ' ' . $middle_name . ' ' . $last_name;
                                                                                        echo '<option value="' . $emp_id . '">' . $employee_name . ' (' . $designation . ')</option>';
                                                                                    }
                                                                                }

                                                                                $stmt->close();

                                                                            } else {
                                                                                // Output the first option as "Select employee" and disabled
                                                                                echo '<option value="" disabled selected>Select employee</option>';

                                                                                // Prepare the base query to fetch employees
                                                                                $query = "SELECT emp_id, first_name, middle_name, last_name, designation, department, role, is_supervisor 
                                                                                        FROM tblemployees WHERE 1=1";

                                                                                // Adjust the query based on the role of the logged-in user
                                                                                if ($session_role == 'Admin') {
                                                                                    $query .= " AND can_be_assigned = 'YES'";
                                                                                } elseif ($session_role == 'Manager') {
                                                                                    $query .= " AND department = ? AND role != 'Admin'";
                                                                                } elseif ($session_role == 'Staff' && $session_supervisor == 1) {
                                                                                    $query .= " AND supervisor_id = ?";
                                                                                }

                                                                                // Execute the adjusted query
                                                                                $stmt = $conn->prepare($query);

                                                                                // Bind parameters based on the role
                                                                                if ($session_role == 'Manager') {
                                                                                    $stmt->bind_param("s", $session_depart);
                                                                                } elseif ($session_role == 'Staff' && $session_supervisor == 1) {
                                                                                    $stmt->bind_param("i", $session_id);
                                                                                }

                                                                                $stmt->execute();
                                                                                $stmt->bind_result($emp_id, $first_name, $middle_name, $last_name, $designation, $department, $role, $is_supervisor);

                                                                                // Fetch and populate the dropdown with options
                                                                                while ($stmt->fetch()) {
                                                                                    $employee_name = $first_name . ' ' . $middle_name . ' ' . $last_name;
                                                                                    echo '<option value="' . $emp_id . '">' . $employee_name . ' (' . $designation . ')</option>';
                                                                                }

                                                                                $stmt->close();
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-6 mobile-inputs">
                                                            <h4 class="sub-title"></h4>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-6">
                                                                        <label for="start_date" class="block">Start Date</label>
                                                                        <input name="start_date" id="dropper-animation" class="form-control start_date" type="text" autocomplete="off" placeholder="" value="<?php echo isset($row['start_date']) ? $row['start_date'] : ''; ?>">
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label for="due_date" class="block">End Date</label>
                                                                         <input id="dropper-default" class="form-control due_date" name="due_date" type="text" autocomplete="off" placeholder="" value="<?php echo isset($row['due_date']) ? $row['due_date'] : ''; ?>">
                                                                    </div>
                                                                </div>
                                                                <?php if(isset($row) && !empty($row)): ?>
                                                                <?php
                                                                    $selected_priority = isset($row['priority']) ? $row['priority'] : '';
                                                                ?>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <h4 class="sub-title">Priority</h4>
                                                                        <div class="form-radio">
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="priority" value="High" <?php echo $selected_priority == 'High' ? 'checked' : ''; ?>>
                                                                                    <i class="helper"></i>High
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="priority" value="Medium" <?php echo $selected_priority == 'Medium' ? 'checked' : ''; ?>>
                                                                                    <i class="helper"></i>Medium
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="priority" value="Low" <?php echo $selected_priority == 'Low' ? 'checked' : ''; ?>>
                                                                                    <i class="helper"></i>Low
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php else: ?>
                                                                <h4 class="sub-title">Priority</h4>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-radio">
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="priority" value="High">
                                                                                    <i class="helper"></i>High
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="priority" value="Medium">
                                                                                    <i class="helper"></i>Medium
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio" name="priority" value="Low">
                                                                                    <i class="helper"></i>Low
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-5"></label>
                                                    <div class="form-group">
                                                        <textarea name="description" id="summernote" cols="30" rows="10" class="form-control summernote"><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea>   
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-sm-5"></label>
                                                        <div class="col-sm-5">
                                                            <?php if(isset($row) && !empty($row)): ?>
                                                                <button id="tasks-update" type="submit" class="btn btn-primary m-b-0">Update</button>
                                                            <?php else: ?>
                                                                <button id="tasks-add" type="submit" class="btn btn-primary m-b-0">Submit</button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Basic Inputs Validation end -->
                                            
                                        </div>
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
    <?php include('../includes/scripts.php')?>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
    <script>
        function convertDateFormat(dateStr) {
            if (dateStr.includes('/')) {
                var parts = dateStr.split('/');
                return `${parts[2]}-${parts[0].padStart(2, '0')}-${parts[1].padStart(2, '0')}`;
            } else {
                return dateStr;
            }
        }

        $('#tasks-update').click(function(event){
            event.preventDefault();
            (async () => {
                var startDate = convertDateFormat($('#dropper-animation').val());
                var dueDate = convertDateFormat($('#dropper-default').val());

                console.log("START DATE HERE: " + startDate);
                console.log("DUE DATE HERE: " + dueDate);

                if (!startDate || !dueDate) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                var data = {
                    id: <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>,
                    title: $('#title').val(),
                    description: $('#summernote').summernote('code'),
                    assigned_to: $('#assigned_to').val(),
                    priority: $('input[name="priority"]:checked').val(),
                    start_date: startDate,
                    due_date: dueDate,
                    action: "tasks-update",
                };

                if (data.title === '' || data.description === '' || 
                    data.priority === '' || data.assigned_to === '' || data.start_date === '' || 
                    data.due_date === '' || data.id === 'null') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please all fieds are required. Kindly fill all',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                console.log('Data HERE: ' + JSON.stringify(data));
                $.ajax({
                    url: '../admin/task_functions.php',
                    type: 'post',
                    data: data,
                    success:function(response){
                        console.log('success function called');
                        response = JSON.parse(response);
                        console.log('RESPONSE HERE: ' + response.status)
                        console.log(`RESPONSE HERE: ${response.message}`);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                html: response.message,
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "my_task_list.php";
                                    // location.reload();
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
                        console.log('AJAX Data HERE: ' + JSON.stringify(data));
                        console.log("Response from server: " + jqXHR.responseText);
                        console.log("Status:", status);
                        console.log("Error:", error);
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        Swal.fire({
                                icon: 'error',
                                text: jqXHR.responseText,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                    }
                });
            })()
        })
    </script>
    <script>
        $('#tasks-add').click(function(event){
            event.preventDefault(); // prevent the default form submission
            (async () => {
                var startDate = convertDateFormat($('#dropper-animation').val());
                var dueDate = convertDateFormat($('#dropper-default').val());

                console.log("START DATE HERE: " + startDate);
                console.log("DUE DATE HERE: " + dueDate);

                if (!startDate || !dueDate) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                var data = {
                    title: $('#title').val(),
                    description: $('#summernote').summernote('code'),
                    assigned_to: $('#assigned_to').val(),
                    priority: $('input[name="priority"]:checked').val(),
                    start_date: startDate,
                    due_date: dueDate,
                    status: "Pending",
                    action: "tasks-add",
                };

                if (data.title === '' || data.description === '' || 
                    data.priority === '' || data.assigned_to === '' || data.start_date === '' || 
                    data.due_date === '') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please all fieds are required. Kindly fill all',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                console.log("START DATE HERE: "+ $('#dropper-animation').val())
                console.log('Data HERE: ' + JSON.stringify(data));
                $.ajax({
                    url: '../admin/task_functions.php',
                    type: 'post',
                    data: data,
                    success:function(response) {
                        console.log('success function called');
                        response = JSON.parse(response);
                        console.log('RESPONSE HERE: ' + response.status)
                        console.log(`RESPONSE HERE: ${response.message}`);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                html: response.message,
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
                                text: response.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Data HERE: ' + JSON.stringify(data));
                        console.log("Response from server: " + jqXHR.responseText);
                        console.log("Status:", status);
                        console.log("Error:", error);
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        Swal.fire({
                                icon: 'error',
                                text: jqXHR.responseText,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                    }
                });
            })()
        })
    </script>
    <!-- Summernote JS - CDN Link -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#summernote").summernote({
                height: 200,
                toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview', 'help']]
                ],
                fontNames: ['Arial', 'Arial Black', 'Courier New', 'Georgia', 'Impact', 'Lucida Console', 'Tahoma', 'Times New Roman', 'Trebuchet MS', 'Verdana', 'Comic Sans MS', 'Palatino Linotype', 'Segoe UI', 'Open Sans', 'Source Sans Pro'],
                fontSizes: ['12', '14', '16', '18', '20', '22', '24', '28', '32'],
                callbacks: {
                onChangeFont: function(fontName) {
                    // Preserve font size when changing font family
                    var currentFontSize = $(this).summernote('fontSize');
                    $(this).summernote('fontName', fontName);
                    $(this).summernote('fontSize', currentFontSize);
                }
                },
                onInit: function() {
                $(this).summernote('fontName', 'Arial');
                $(this).summernote('fontSize', '16');
                }
            });
        });

    </script>
    <!-- //Summernote JS - CDN Link -->
 </body>

</html>
