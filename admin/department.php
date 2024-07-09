<?php include('../includes/header.php')?>

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
    $createDate = $departmentRow['creation_date'];

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
        'createDate' => $createDate,
        'staffCount' => $staffCount,
        'managerCount' => $managerCount,
    ];
}
?>

<body>
<style>
    .notification {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        padding: 10px;
        margin: 10px;
        border-radius: 4px;
        z-index: 9999;
        background-color: #AFF29A;
        border: 2px solid #35DB00;
        color: #104300;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .notification-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 5px;
        padding-bottom: 5px;
        border-bottom: 1px solid #35DB00;
    }

    .notification-icon {
        margin-right: 10px;
        font-size: 18px;
        line-height: 20px;
        text-align: center;
        width: 20px;
    }

    .notification-title {
        margin: 0;
        font-size: 16px;
    }

    .notification-message {
        margin-top: 5px;
        padding: 10px 0;
    }

    .swal2-container {
      z-index: 99999;
    }

    .notification-close {
        border: none;
        background-color: transparent;
        font-size: 18px;
        line-height: 20px;
        color: #0a0a0a;
        cursor: pointer;
    }

    .notification-close:hover {
        color: #0a0a0a;
    }

    .md-content h3 {
        color: #fff;
        margin: 0;
        padding: 0.3em;
        text-align: center;
        font-weight: 300;
        opacity: 0.7;
        background: #01a9ac;
        border-radius: 3px 3px 0 0;
}
</style>

<div style="display: none; width: 270px; right: 36px; top: 36px;" id="notification" class="notification success">
    <div class="notification-header">
        <div class="notification-icon"><i class="icofont icofont-info-circle"></i></div>
        <h4 class="notification-title">Success</h4>
        <button class="notification-close"><i class="icofont icofont-close-circled"></i></button>
    </div>
    <div class="notification-message">Success message goes here.</div>
</div>


<!-- Pre-loader start -->
<?php include('../includes/loader.php')?>
<!-- Pre-loader end -->
<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

        <?php include('../includes/topbar.php')?>

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">
                
               <?php $page_name = "department"; ?>
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
                                                <div class="d-inline"  id="pnotify-desktop-success">
                                                    <h4>Department List</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" col-lg-4">
                                            <button type="button" class="btn btn-primary waves-effect waves-light f-right d-inline-block md-trigger" data-modal="modal-13"> <i class="icofont icofont-plus m-r-5"></i> New Department
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Page-header end -->
                                    <!-- Page body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <!-- project  start -->
                                        <?php foreach ($departments as $department): ?>
                                        <div class="col-md-12 col-xl-6 ">
                                            <div class="card app-design">
                                                <div class="card-block">
                                                    <div class="f-right">
                                                        <div class="dropdown-secondary dropdown">
                                                            <button class="btn btn-primary btn-mini dropdown-toggle waves-effect waves-light" type="button" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $department['name'] ?></button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdown1" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                                <a class="dropdown-item waves-light waves-effect" href="staff_list.php?department=<?= urlencode($department['name']) ?>"><span class="point-marker bg-danger"></span>View Staff</a>
                                                                <a class="dropdown-item waves-light waves-effect edit-btn md-trigger" href="#" data-modal="modal-13" data-original-title="Edit" data-id="<?= $department['id'] ?>" data-name="<?= $department['name'] ?>" data-description="<?= $department['desc'] ?>"><span class="point-marker bg-warning"></span>Edit</a>
                                                                <a class="dropdown-item waves-light waves-effect delete-btn" href="#" data-original-title="Delete" data-id="<?= $department['id'] ?>"><span class="point-marker bg-success"></span>Delete</a>
                                                            </div>
                                                            <!-- end of dropdown menu -->
                                                        </div>
                                                    </div>
                                                    <h6 class="f-w-400 text-muted"><?= $department['desc'] ?></h6>
                                                    <p class="text-c-blue f-w-400">
                                                        <?php
                                                        $createDate = strtotime($department['createDate']); 
                                                        echo date('jS F, Y', $createDate);
                                                        ?>
                                                    </p>
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
                                        <!-- project  end -->
                                        </div>
                                        <!-- Add Contact Start Model start-->
                                           <div class="md-modal md-effect-13 addcontact" id="modal-13">
                                                <div class="md-content" style="max-width: 400px;">
                                                    <h3 class="f-26">Add Department</h3>
                                                    <div >
                                                         <input hidden type="text" class="form-control department-id" name="department-id">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="icofont icofont-bank-alt"></i></span>
                                                            <input type="text" class="form-control dname" name="dname" placeholder="Department Name">
                                                        </div>
                                                        <div class="input-group">
                                                            <textarea class="form-control description" name="description" placeholder="Description here" spellcheck="false" rows="5"></textarea>
                                                        </div>
                                                        
                                                        <div class="text-center">
                                                            <button type="submit"  id="save-btn" class="btn btn-primary waves-effect m-r-20 f-w-600 d-inline-block save_btn">Save</button>
                                                            <button type="submit" id="update-btn" class="btn btn-primary waves-effect m-r-20 f-w-600 update_btn" style="display:none;">Update</button>
                                                            <button type="button" class="btn btn-primary waves-effect m-r-20 f-w-600 md-close d-inline-block close_btn">Close</button>
                                                        </div>
                                                    </div>
                                               </div>
                                          </div>
                                        <div class="md-overlay"></div>
                                        <!-- Add Contact Ends Model end-->
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
    <script type="text/javascript">
        $('#save-btn').click(function(event){
            event.preventDefault(); // prevent the default form submission
            (async () => {
                var data = {
                    dname: $('.dname').val(),
                    description: $('.description').val(), 
                    action: "save",
                };
                if (data.dname.trim() === '' || data.description.trim() === '') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please all fieds are required. Kindly fill all',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                $.ajax({
                    url: 'department_functions.php',
                    type: 'post',
                    data: data,
                    success:function(response){
                        response = JSON.parse(response);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Inserted Successfully',
                                html:
                                'Name : ' + data['dname'],
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('.md-close').trigger('click'); // close the modal form
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
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            })()
        })
    </script>
    <script type="text/javascript">
       // Get the edit button and listen for click events
        $('.edit-btn').click(function() {
            // Get the values of the data attributes from the clicked edit button
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            
            // Set the values of the input fields in the modal form
            $('#modal-13 .department-id').val(id);
            $('#modal-13 .dname').val(name);
            $('#modal-13 .description').val(description);
            
            // Show the modal form
            $('.md-modal[data-modal="modal-13"]').addClass('md-show');

            $('#save-btn').removeClass('d-inline-block').hide();
            $('#update-btn').addClass('d-inline-block').show();

        });

        $('#update-btn').click(function(event){
            event.preventDefault(); // prevent the default form submission
            (async () => {
                var data = {
                    id: $('.department-id').val(),
                    dname: $('.dname').val(),
                    description: $('.description').val(),
                    action: "update",
                };
                if (data.dname.trim() === '' || data.description.trim() === '') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please all fieds are required. Kindly fill all',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                $.ajax({
                    url: 'department_functions.php',
                    type: 'post',
                    data: data,
                    success:function(response){
                        response = JSON.parse(response);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated Successfully',
                                html:
                                'Name : ' + data['dname'],
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('.md-close').trigger('click'); // close the modal form
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
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            })()
        })
    </script>
    <script type="text/javascript">
        $('.delete-btn').click(function(){
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

            if (formValues) {
            var data = {
                id: $(this).data("id"),
                action: "delete"
            };

            $.ajax({
                url: 'department_functions.php',
                type: 'post',
                data: data,
                success: function(response) {
                    const responseObject = JSON.parse(response);
                    console.log(`RESPONSE HERE: ${responseObject.status}`);
                    if (response && responseObject.status === 'success') {
                        // Show success message
                        Swal.fire(
                            'Deleted!',
                            'Department has been deleted.',
                            'success'
                        ).then((result) => {
                                if (result.isConfirmed) {
                                   
                                    location.reload();
                                }
                        });
                        
                    } else {
                        // Show error message
                        Swal.fire(
                            'Error!',
                            'Failed to delete department.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX error: " + error);
                    Swal.fire('Error!', 'Failed to delete department.', 'error');
                }

            });
          }
        })()
    })
    </script>

</body>

</html>
