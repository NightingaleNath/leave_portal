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
                <?php $page_name = "customer_list"; ?>
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
                                                    <h4>Customer List</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-header end -->
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <!--profile cover end-->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <!-- tab content start -->
                                                <div class="tab-content">
                                                    <!-- tab pane contact start -->
                                                    <div class="tab-pane active" id="contacts" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <!-- contact data table card start -->
                                                                        <div class="card">
                                                                            <div class="card-block contact-details">
                                                                                <div class="data_table_main table-responsive dt-responsive">
                                                                                   <?php
                                                                                    $stmt = mysqli_prepare($conn, "SELECT id, firstname, lastname, middlename, contact, address, email FROM customers ORDER BY date_created DESC");
                                                                                    mysqli_stmt_execute($stmt);
                                                                                    mysqli_stmt_store_result($stmt);
                                                                                    mysqli_stmt_bind_result($stmt, $id, $firstname, $lastname, $middlename, $contact, $address, $email);
                                                                                    $count = 1;
                                                                                    ?>
                                                                                    <table id="simpletable" class="table table-striped table-bordered">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>#</th>
                                                                                                <th>Name</th>
                                                                                                <th>Contact</th>
                                                                                                <th>Address</th>
                                                                                                <th>Email</th>
                                                                                                <th>Action</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            if (mysqli_stmt_num_rows($stmt) <= 0) {
                                                                                                echo '<tr><td colspan="6" class="text-center"><img src="..\files\assets\images\no_data.png" class="img-radius" alt="No Data Found" style="width: 200px; height: auto;"></td></tr>';
                                                                                            } else {
                                                                                                while (mysqli_stmt_fetch($stmt)) {
                                                                                            ?>
                                                                                                    <tr>
                                                                                                        <td><?= $count ?></td>
                                                                                                        <td><?= $firstname . " " . $middlename . " " . $lastname ?></td>
                                                                                                        <td><?= $contact ?></td>
                                                                                                        <td><?= $address ?></td>
                                                                                                        <td><?= $email ?></td>
                                                                                                        <td class="dropdown">
                                                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" aria-hidden="true"></i></button>
                                                                                                            <div class="dropdown-menu dropdown-menu-right b-none contact-menu">
                                                                                                                <a class="dropdown-item" href="new_customer.php?id=<?= $id ?>&edit=1"><i class="icofont icofont-edit"></i>Edit</a>
                                                                                                                <a class="delete-customer dropdown-item" href="#"><i class="delete-customer icofont icofont-ui-delete" data-id="<?= $id ?>"></i>Delete</a>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                            <?php
                                                                                                    $count++;
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <?php
                                                                                    mysqli_stmt_close($stmt);
                                                                                    ?>
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
                                            </div>
                                        </div>
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
        $('.delete-customer').click(function(){
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
                const id = $(this).find('i').data('id');
                console.log('id:', id); 

                if (formValues) {
                var data = {
                    id: id,
                    action: "delete-customer"
                };
                console.log('Data HERE: ' + JSON.stringify(data));
                $.ajax({
                    url: 'customer_functions.php',
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
                        Swal.fire('Error!', 'Failed to delete department.', 'error');
                    }

                });
            }
            })()
        })
    </script>

</body>

</html>
