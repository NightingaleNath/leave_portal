<?php include('../includes/header.php')?>

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
                <?php $page_name = "new_ticket"; ?>
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
                                                    <h4>New Ticket</h4>
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
                                                        $stmt = mysqli_prepare($conn, "SELECT * FROM tickets WHERE id = ?");
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
                                                                            <label for="userName-2" class="block">Subject</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="subject" name="subject"autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['subject']) ? $row['subject'] : ''; ?>">
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <?php if ($_SESSION['srole'] == 1 || $_SESSION['srole'] == 2): ?>
                                                                            <div class="col-sm-12">
                                                                                <label for="userName-2" class="block">Customer</label>
                                                                            </div>
                                                                            <div class="col-sm-12">
                                                                                <select class="js-example-disabled-results col-sm-12" name="customer" id="customer" required>
                                                                                    <?php
                                                                                        // Check if we are coming from an edit page and $selected_department_id is not empty
                                                                                        if (!empty($row['customer_id'])) {
                                                                                                // Query the database to get the department details
                                                                                                $stmt = mysqli_prepare($conn, "SELECT id, firstname, middlename, lastname, contact FROM customers WHERE id = ?");
                                                                                                mysqli_stmt_bind_param($stmt, "i", $row['customer_id']);
                                                                                                mysqli_stmt_execute($stmt);
                                                                                                mysqli_stmt_bind_result($stmt, $id, $firstname, $middlename, $lastname, $contact);
                                                                                                mysqli_stmt_fetch($stmt);
                                                                                                mysqli_stmt_close($stmt);
                                                                                                // Output the selected option
                                                                                                echo '<option value="' . $id . '" selected>' . $firstname . ' ' . $middlename . ' ' . $lastname . '</option>';
                                                                                                // Output the rest of the options
                                                                                                $stmt = mysqli_prepare($conn, "SELECT id, firstname, middlename, lastname, contact FROM customers");
                                                                                                mysqli_stmt_execute($stmt);
                                                                                                mysqli_stmt_store_result($stmt);
                                                                                                mysqli_stmt_bind_result($stmt, $id, $firstname, $middlename, $lastname, $contact);
                                                                                                while (mysqli_stmt_fetch($stmt)) {
                                                                                                    echo '<option value="' . $id . '">' . $firstname . ' ' . $middlename . ' ' . $lastname . '</option>';
                                                                                                }
                                                                                                mysqli_stmt_close($stmt);
                                                                                        } else {
                                                                                            // Output the first option as "Select department" and disabled
                                                                                                echo '<option value="" disabled selected>Select customer</option>';
                                                                                                // Output the rest of the options
                                                                                                $stmt = mysqli_prepare($conn, "SELECT id, firstname, middlename, lastname, contact FROM customers");
                                                                                                mysqli_stmt_execute($stmt);
                                                                                                mysqli_stmt_store_result($stmt);
                                                                                                mysqli_stmt_bind_result($stmt, $id, $firstname, $middlename, $lastname, $contact);
                                                                                                while (mysqli_stmt_fetch($stmt)) {
                                                                                                echo '<option value="' . $id . '">' . $firstname . ' ' . $middlename . ' ' . $lastname . '</option>';
                                                                                                }
                                                                                                mysqli_stmt_close($stmt);
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <input type="text" name="customer" id="customer" class="form-control" value="<?php echo isset($row['customer_id']) ? $row['customer_id'] : $_SESSION['slogin']; ?>" hidden>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="col-sm-6 mobile-inputs">
                                                                <h4 class="sub-title"></h4>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Department</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <select class="js-example-disabled-results col-sm-12" name="department" id="department" required>
                                                                                <?php
                                                                                    // Check if we are coming from an edit page and $selected_department_id is not empty
                                                                                    if (!empty($row['department_id'])) {
                                                                                            // Query the database to get the department details
                                                                                            $stmt = mysqli_prepare($conn, "SELECT id, name FROM departments WHERE id = ?");
                                                                                            mysqli_stmt_bind_param($stmt, "i", $row['department_id']);
                                                                                            mysqli_stmt_execute($stmt);
                                                                                            mysqli_stmt_bind_result($stmt, $id, $name);
                                                                                            mysqli_stmt_fetch($stmt);
                                                                                            mysqli_stmt_close($stmt);
                                                                                            // Output the selected option
                                                                                            echo '<option value="' . $id . '" selected>' . $name . '</option>';
                                                                                            // Output the rest of the options
                                                                                            $stmt = mysqli_prepare($conn, "SELECT id, name, description FROM departments");
                                                                                            mysqli_stmt_execute($stmt);
                                                                                            mysqli_stmt_store_result($stmt);
                                                                                            mysqli_stmt_bind_result($stmt, $id, $name, $description);
                                                                                            while (mysqli_stmt_fetch($stmt)) {
                                                                                                echo '<option value="' . $id . '">' . $name . '</option>';
                                                                                            }
                                                                                            mysqli_stmt_close($stmt);
                                                                                    } else {
                                                                                        // Output the first option as "Select department" and disabled
                                                                                            echo '<option value="" disabled selected>Select department</option>';
                                                                                            // Output the rest of the options
                                                                                            $stmt = mysqli_prepare($conn, "SELECT id, name, description FROM departments");
                                                                                            mysqli_stmt_execute($stmt);
                                                                                            mysqli_stmt_store_result($stmt);
                                                                                            mysqli_stmt_bind_result($stmt, $id, $name, $description);
                                                                                            while (mysqli_stmt_fetch($stmt)) {
                                                                                                echo '<option value="' . $id . '">' . $name . '</option>';
                                                                                            }
                                                                                            mysqli_stmt_close($stmt);
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <?php if(isset($row) && !empty($row)): ?>
                                                                        <div></div>
                                                                    <?php else: ?>
                                                                        <h4 class="sub-title">Priority</h4>
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-12">
                                                                                <div class="form-radio">
                                                                                    <div class="radio radiofill radio-inline">
                                                                                        <label>
                                                                                            <input type="radio" name="priority" value="Highest" checked="checked">
                                                                                            <i class="helper"></i>Highest
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="radio radiofill radio-inline">
                                                                                        <label>
                                                                                            <input type="radio" name="priority" value="High">
                                                                                            <i class="helper"></i>High
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="radio radiofill radio-inline">
                                                                                        <label>
                                                                                            <input type="radio" name="priority" value="Normal">
                                                                                            <i class="helper"></i>Normal
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
                                                                    <button id="tickets-update" type="submit" class="btn btn-primary m-b-0">Update</button>
                                                                <?php else: ?>
                                                                    <button id="tickets-add" type="submit" class="btn btn-primary m-b-0">Submit</button>
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
        $('#tickets-update').click(function(event){
            event.preventDefault(); // prevent the default form submission
            (async () => {
                var data = {
                    id: <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>,
                    subject: $('#subject').val(),
                    description: $('#summernote').summernote('code'), // get the HTML content of Summernote
                    department: $('#department').val(),
                    customer: $('#customer').val(),
                    action: "tickets-update",
                };

                if (data.subject.trim() === '' || data.description.trim() === '' || 
                    data.department.trim() === '' || data.customer.trim() === '') {
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
                    url: '../admin/ticket_functions.php',
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
                                    window.location.href = "ticket_list.php";
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
                    }
                });
            })()
        })
    </script>
    <script>
        $('#tickets-add').click(function(event){
            event.preventDefault(); // prevent the default form submission
            (async () => {
                var data = {
                    subject: $('#subject').val(),
                    description: $('#summernote').summernote('code'), // get the HTML content of Summernote
                    priority: $('input[name="priority"]:checked').val(),
                    department: $('#department').val(),
                    customer: $('#customer').val(),
                    status: 0, // set initial status to 0
                    action: "tickets-add",
                };

                if (data.subject === '' || data.description === '' || 
                    data.priority === '' || data.department === '' || 
                    data.customer === '') {
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
                    url: '../admin/ticket_functions.php',
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
