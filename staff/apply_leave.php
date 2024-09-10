<?php include('../includes/header.php') ?>

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

if (isset($_POST['empId'])) {
    $empId = $_POST['empId'];

    // Query the database for leave types assigned to the selected employee
    $sql = "SELECT lt.leave_type, lt.id AS leave_type_id
            FROM tblleavetype lt
            INNER JOIN employee_leave_types elt ON lt.id = elt.leave_type_id
            WHERE elt.emp_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $empId);
    $stmt->execute();
    $result = $stmt->get_result();

    $leaveTypesOptions = '<option value="" selected="">Select Leave type</option>';
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $leaveTypesOptions .= '<option value="' . htmlspecialchars($row['leave_type_id']) . '">' . htmlspecialchars($row['leave_type']) . '</option>';
        }
    } else {
        $leaveTypesOptions .= '<option value="" disabled>No leave types assigned</option>';
    }

    echo $leaveTypesOptions;
    exit; // Ensure the script stops executing after handling the AJAX request
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
                    <?php $page_name = "apply_leave"; ?>
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
                                                        <h4>Leave Portal - Apply leave</h4>
                                                        <span>Send all your leave request from here</span>
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
                                                <div class="card">
                                                    <div class="card-block">
                                                        <div class="j-wrapper j-wrapper-640">
                                                            <form method="post" class="j-pro" id="j-pro" enctype="multipart/form-data" novalidate="">
                                                                <div class="j-content">
                                                                    <div class="j-wrapper">
                                                                        <h4 style="text-align: center;">Create New Leave</h4>
                                                                    </div>
                                                                    <?php
                                                                    // Check if the user role is Admin and designation is Administrator
                                                                    $userRole = $_SESSION['srole'];
                                                                    $userDesignation = $_SESSION['sdesignation'];

                                                                    // Fetch all employees from the database
                                                                    $sql = "SELECT emp_id, first_name, middle_name, last_name FROM tblemployees";
                                                                    $result = mysqli_query($conn, $sql);

                                                                    $employeeOptions = '<option value="" disabled selected>Select Employee</option>';
                                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                                            $employeeOptions .= '<option value="' . htmlspecialchars($row['emp_id']) . '">' . htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']) . '</option>';
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <div class="j-unit">
                                                                        <?php if ($userRole === 'Admin' && $userDesignation === 'Administrator'): ?>
                                                                            <select class="js-example-disabled-results col-sm-12" name="empId" id="empId" required>
                                                                                <?php echo $employeeOptions; ?>
                                                                            </select>
                                                                        <?php else: ?>
                                                                            <input type="hidden" id="empId" value="<?php echo htmlspecialchars($_SESSION['slogin']); ?>">
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <!-- End of Employee list -->
                                                                    <!-- Start Leave type -->
                                                                    <div class="j-unit">
                                                                        <label class="j-input j-select">
                                                                            <select name="leave_type" id="leave_type">
                                                                                <option value="" selected="">Select Leave type</option>
                                                                            </select>
                                                                            <i></i>
                                                                        </label>
                                                                    </div>
                                                                    <!-- end leave type -->
                                                                    <!-- start date -->
                                                                    <div class="j-unit">
                                                                        <div class="j-input">
                                                                            <span style="margin-bottom: 8px;" class="j-hint">Start Date</span>
                                                                            <input id="start_date" name="start_date" class="form-control" type="date">
                                                                            <span class="j-tooltip j-tooltip-right-top">Pick your start leave date</span>
                                                                        </div>
                                                                    </div>
                                                                    <!-- end start date -->
                                                                    <!-- start date -->
                                                                    <div class="j-unit">
                                                                        <div class="j-input">
                                                                            <span style="margin-bottom: 8px;" class="j-hint">End Date</span>
                                                                            <input id="end_date" name="end_date" class="form-control" type="date">
                                                                            <span class="j-tooltip j-tooltip-right-top">Pick your end leave date</span>
                                                                        </div>
                                                                    </div>
                                                                    <!-- end start date -->
                                                                    <!-- start Number Days -->
                                                                    <div class="j-unit">
                                                                        <div class="j-input">
                                                                            <label class="j-icon-right" for="number_days">
                                                                                <i class="icofont icofont-math"></i>
                                                                            </label>
                                                                            <input type="text" id="number_days" value="0" name="number_days" readonly disabled>
                                                                        </div>
                                                                    </div>
                                                                    <!-- end Number days -->
                                                                    <!-- start remarks -->
                                                                    <div class="j-unit">
                                                                        <div class="j-input">
                                                                            <textarea placeholder="Additional info" spellcheck="true" name="remarks" id="remarks"></textarea>
                                                                            <span class="j-tooltip j-tooltip-right-top">Any information about the leave</span>
                                                                        </div>
                                                                    </div>
                                                                    <!-- end remarks -->
                                                                    <!-- start files -->
                                                                    <div class="j-unit" id="sick_file_container" style="display: none;">
                                                                        <div class="j-input j-append-small-btn">
                                                                            <div class="j-file-button">
                                                                                Browse
                                                                                <input type="file" name="sick_file" id="sick_file" accept=".pdf, .jpg, .jpeg, .png" onchange="validateFile(this)">
                                                                            </div>
                                                                            <input type="text" id="sick_file_input" readonly="" placeholder="For sick leave only">
                                                                            <span class="j-hint">Only: pdf, jpg, jpeg, png, less than 2MB</span>
                                                                        </div>
                                                                    </div>
                                                                    <!-- end files -->
                                                                </div>
                                                                <!-- end /.content -->
                                                                <div class="j-footer">
                                                                    <div id="loader-wrapper" style="display: none;">
                                                                        <div class="preloader3 loader-block" style="height: 70px;">
                                                                            <div class="circ1"></div>
                                                                            <div class="circ2"></div>
                                                                            <div class="circ3"></div>
                                                                            <div class="circ4"></div>
                                                                        </div>
                                                                    </div>
                                                                    <button id="apply-leave" type="submit" class="btn btn-primary">Submit</button>
                                                                    <button type="reset" class="btn btn-default m-r-20">Reset</button>
                                                                </div>
                                                                <!-- end /.footer -->
                                                            </form>
                                                        </div>
                                                        <label class="col-sm-5"></label>
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
    <?php include('../includes/scripts.php') ?>

    <script>
        $(document).ready(function() {
            $('#apply-leave').click(function(event) {
                event.preventDefault();

                $('#apply-leave').prop('disabled', true);
                $('#loader-wrapper').fadeIn();

                // Collect form data
                var formData = new FormData();
                var empId = $('#empId').val();
                var leaveType = $('#leave_type').val();
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var numberDays = $('#number_days').val();
                var remarks = $('#remarks').val();
                var sickFile = $('#sick_file')[0].files[0];

                // Validate fields
                if (!empId || !leaveType || !startDate || !endDate || !numberDays || numberDays <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    $('#apply-leave').prop('disabled', false);
                    $('#loader-wrapper').fadeOut();
                    return;
                }

                var selectedLeaveType = $('#leave_type option:selected').text().toLowerCase();
                if ((selectedLeaveType.includes('sick') || selectedLeaveType === 'sick leave') && !sickFile) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please upload a file for sick leave.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    $('#apply-leave').prop('disabled', false);
                    $('#loader-wrapper').fadeOut();
                    return;
                }

                // Append data to FormData
                formData.append('empId', empId);
                formData.append('leave_type', leaveType);
                formData.append('start_date', startDate);
                formData.append('end_date', endDate);
                formData.append('number_days', numberDays);
                formData.append('remarks', remarks);
                formData.append('sick_file', sickFile);
                formData.append('action', 'apply-leave');

                // AJAX request to submit the form data
                $.ajax({
                    url: '../admin/leave_functions.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#apply-leave').prop('disabled', false);
                        $('#loader-wrapper').fadeOut();
                        try {
                            response = JSON.parse(response);
                            console.log('RESPONSE HERE: ' + response.status);
                            console.log(`RESPONSE HERE: ${response.message}`);
                            if (response.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    text: response.message,
                                    confirmButtonColor: '#01a9ac',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                console.log('RESPONSE HERE: ' + response.status);
                                Swal.fire({
                                    icon: 'error',
                                    text: response.message,
                                    confirmButtonColor: '#eb3422',
                                    confirmButtonText: 'OK'
                                });
                            }
                        } catch (e) {
                            console.error("Parsing error:", e);
                            console.error("Received response:", response);
                            Swal.fire({
                                icon: 'error',
                                text: 'An unexpected error occurred. Please try again.',
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#apply-leave').prop('disabled', false);
                        $('#loader-wrapper').fadeOut();
                        console.log('AJAX Data HERE: ' + JSON.stringify(formData));
                        console.log("Response from server: " + jqXHR.responseText);
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        Swal.fire({
                            icon: 'error',
                            text: 'AJAX error: ' + textStatus + ' : ' + errorThrown,
                            confirmButtonColor: '#eb3422',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            function loadLeaveTypes(empId) {
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: {
                        empId: empId
                    },
                    success: function(response) {
                        $('#leave_type').html(response);
                    }
                });
            }

            $('#empId').change(function() {
                var empId = $(this).val();
                loadLeaveTypes(empId);
            });

            // Automatically load leave types for non-Admin users
            <?php if ($userRole !== 'Admin' || $userDesignation !== 'Administrator'): ?>
                var empId = $('#empId').val();
                loadLeaveTypes(empId);
            <?php endif; ?>

            // Show or hide the file input based on the leave type
            $('#leave_type').change(function() {
                var selectedLeaveType = $(this).find('option:selected').text().toLowerCase();
                if (selectedLeaveType.includes('sick') || selectedLeaveType === 'sick leave') {
                    $('#sick_file_container').show();
                } else {
                    $('#sick_file_container').hide();
                }
            });

        });
    </script>
    <script>
        function validateFile(input) {
            var file = input.files[0];
            var fileType = file.type.toLowerCase();
            var fileSize = file.size; // in bytes

            // Allowed file types and maximum file size (2MB)
            var allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            var maxSize = 2 * 1024 * 1024; // 2MB

            if (!allowedTypes.includes(fileType)) {
                Swal.fire({
                    icon: 'error',
                    text: 'Invalid file type. Please select a PDF, JPG, or PNG file.',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK'
                });
                input.value = '';
                return false;
            }

            if (fileSize > maxSize) {
                Swal.fire({
                    icon: 'error',
                    text: 'File size exceeds the limit of 2MB. Please select a smaller file.',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'OK'
                });
                input.value = '';
                return false;
            }

            // Display the selected file name in the input field
            var fileName = file.name;
            $('#sick_file_input').val(fileName);
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const numberDaysInput = document.getElementById('number_days');

            // Set the min attribute to today's date
            const today = new Date().toISOString().split('T')[0];
            startDateInput.setAttribute('min', today);
            endDateInput.setAttribute('min', today);

            function handleDateInput(input) {
                input.addEventListener('input', function() {
                    const date = new Date(this.value);
                    const day = date.getUTCDay();

                    // Prevent selecting weekends
                    if (day === 0 || day === 6) { // 0 for Sunday and 6 for Saturday
                        Swal.fire({
                            icon: 'warning',
                            text: 'Weekends are not allowed. Please select a weekday.',
                            confirmButtonColor: '#ffc107',
                            confirmButtonText: 'OK'
                        });
                        this.value = '';
                        return;
                    }

                    // Ensure end date is not less than start date
                    if (input.id === 'end_date' && startDateInput.value && new Date(this.value) < new Date(startDateInput.value)) {
                        Swal.fire({
                            icon: 'warning',
                            text: 'End date cannot be earlier than start date. Please select a valid date.',
                            confirmButtonColor: '#ffc107',
                            confirmButtonText: 'OK'
                        });
                        this.value = '';
                        return;
                    }

                    // Ensure start date is not more than end date
                    if (input.id === 'start_date' && endDateInput.value && new Date(this.value) > new Date(endDateInput.value)) {
                        Swal.fire({
                            icon: 'warning',
                            text: 'Start date cannot be later than end date. Please select a valid date.',
                            confirmButtonColor: '#ffc107',
                            confirmButtonText: 'OK'
                        });
                        this.value = '';
                        return;
                    }

                    calculateDays();
                });

                input.addEventListener('focus', function() {
                    const datePicker = this;
                    const observer = new MutationObserver(function() {
                        const calendarDays = datePicker.ownerDocument.querySelectorAll('td[data-date]');
                        calendarDays.forEach(day => {
                            const date = new Date(day.getAttribute('data-date'));
                            if (date.getUTCDay() === 0 || date.getUTCDay() === 6) {
                                day.classList.add('disabled');
                            }
                        });
                    });
                    observer.observe(datePicker.ownerDocument, {
                        childList: true,
                        subtree: true
                    });
                });
            }

            function isWeekend(date) {
                const day = date.getUTCDay();
                return day === 0 || day === 6;
            }

            function calculateDays() {
                const startDateValue = startDateInput.value;
                const endDateValue = endDateInput.value;

                if (startDateValue && endDateValue) {
                    const startDate = new Date(startDateValue);
                    const endDate = new Date(endDateValue);
                    let count = 0;

                    for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                        if (!isWeekend(d)) {
                            count++;
                        }
                    }

                    numberDaysInput.value = count;
                } else {
                    numberDaysInput.value = '';
                }
            }

            handleDateInput(startDateInput);
            handleDateInput(endDateInput);
        });
    </script>
    <style>
        /* Disable style for weekend days */
        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>

</body>

</html>