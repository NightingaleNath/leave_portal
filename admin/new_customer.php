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
                <?php $page_name = "new_customer"; ?>
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
                                                    <h4>New Customer</h4>
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
                                                        $stmt = mysqli_prepare($conn, "SELECT * FROM customers WHERE id = ?");
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
                                                                <h4 class="sub-title">Personal Details</h4>
                                                                <form>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">First Name *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="firstname" name="firstname"autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['firstname']) ? $row['firstname'] : ''; ?>">
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Middle Name</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="middlename" name="middlename" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['middlename']) ? $row['middlename'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Last Name *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="lastname" name="lastname" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['lastname']) ? $row['lastname'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Contact Number *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="tel" id="contact" name="contact" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['contact']) ? $row['contact'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Address *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="text" id="address" name="address" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['address']) ? $row['address'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="col-sm-6 mobile-inputs">
                                                                <h4 class="sub-title">System Credentials</h4>
                                                                    
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Email *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="email" id="email" name="email" autocomplete="off" class="form-control" placeholder="" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Password *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="password" placeholder="**********" id="password" name="password" autocomplete="off" class="form-control">
                                                                           <?php if(isset($row) && !empty($row)): ?>
                                                                                <label for="userName" class="block" style="font-style: italic; font-size: 12px;">Leave this blank if you don't want to change password</label>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <label for="userName-2" class="block">Confirm Password *</label>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <input type="password" placeholder="**********" id="c_password" name="c_password" autocomplete="off" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                
                                                            </div>
                                                       </div>
                                                       <label class="col-sm-5"></label>
                                                       <div class="row">
                                                            <label class="col-sm-5"></label>
                                                            <div class="col-sm-5">
                                                                <?php if(isset($row) && !empty($row)): ?>
                                                                    <button id="update-customer" type="submit" class="btn btn-primary m-b-0">Update</button>
                                                                <?php else: ?>
                                                                    <button id="customer-add" type="submit" class="btn btn-primary m-b-0">Submit</button>
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
        $('#update-customer').click(function(event){
            event.preventDefault(); // prevent the default form submission
            (async () => {
                var data = {
                    id: <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>,
                    firstname: $('#firstname').val(),
                    middlename: $('#middlename').val(),
                    lastname: $('#lastname').val(),
                    contact: $('#contact').val(),
                    address: $('#address').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    c_password: $('#c_password').val(),
                    action: "update-customer",
                };

                if (data.firstname.trim() === '' || data.lastname.trim() === '' || 
                    data.contact.trim() === '' || data.address.trim() === '' || 
                    data.email.trim() === '') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please all fieds are required. Kindly fill all',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                if (data.password.trim() !== data.c_password.trim()) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Password and Confirm Password do not match',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                console.log('Data HERE: ' + JSON.stringify(data));
                $.ajax({
                    url: 'customer_functions.php',
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
                                    window.location.href = "customer_list.php";
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
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            })()
        })
    </script>
    <script>
        $('#customer-add').click(function(event){
            event.preventDefault(); // prevent the default form submission
            (async () => {
                var data = {
                    firstname: $('#firstname').val(),
                    middlename: $('#middlename').val(),
                    lastname: $('#lastname').val(),
                    contact: $('#contact').val(),
                    address: $('#address').val(),
                    department: $('#department').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    c_password: $('#c_password').val(),
                    action: "customer-add",
                };

                if (data.firstname.trim() === '' || data.lastname.trim() === '' || 
                    data.contact.trim() === '' || data.address.trim() === '' || 
                    data.email.trim() === '' || data.password.trim() === '' || 
                    data.c_password.trim() === '') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please all fieds are required. Kindly fill all',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                if (data.password.trim() !== data.c_password.trim()) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Password and Confirm Password do not match',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                console.log('Data HERE: ' + JSON.stringify(data));
                $.ajax({
                    url: 'customer_functions.php',
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
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            })()
        })
    </script>
    
</body>

</html>
