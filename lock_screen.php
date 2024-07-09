<!DOCTYPE html>
<html lang="en">

<head>
    <title>Client Support System </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <!-- Favicon icon -->
    <link rel="icon" href=".\files\assets\images\favicon.ico" type="image/x-icon">
    <!-- Google font--><link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href=".\files\bower_components\bootstrap\css\bootstrap.min.css">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href=".\files\assets\icon\themify-icons\themify-icons.css">
    <link rel="stylesheet" type="text/css" href=".\files\assets\icon\feather\css\feather.css">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href=".\files\assets\icon\icofont\css\icofont.css">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href=".\files\assets\css\style.css">
</head>

<?php
session_start();
include('includes/config.php');
?>

<body class="fix-menu">
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
            </div>
        </div>
    </div>

    <?php
    if ($_SESSION['srole'] == 1 || $_SESSION['srole'] == 2) {
        // Query the staff table
        $selectQuery = "SELECT lock_unlock FROM staff WHERE email = '{$_SESSION['semail']}'";
    } elseif ($_SESSION['srole'] == 3) {
        // Query the customers table
        $selectQuery = "SELECT lock_unlock FROM customers WHERE email = '{$_SESSION['semail']}'";
    } else {
        // Handle the case when session role is not 1, 2, or 3
        die('Invalid session role');
    }

    $result = mysqli_query($conn, $selectQuery);
    if (!$result) {
        die('Error executing query: ' . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lockUnlock = $row['lock_unlock'] == 'true';
    } else {
        // Handle the case when no row is returned
        $lockUnlock = false;
    }
    $lockUnlockValue = $row['lock_unlock'];

    // Debug information
    // echo "Lock Unlock: " . ($lockUnlock ? 'true' : 'false') . "<br>";
    // echo "Lock Unlock: " . ($lockUnlockValue) . "<br>";
    // $currentURL = $_SERVER['REQUEST_URI'];
    // echo "Current Page URL: " . $currentURL;
    ?>

    <!-- Pre-loader end -->
    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Login card start -->
                    <form method="POST"class="md-float-material form-material">
                        <div class="text-center">
                            <img src=".\files\assets\images\logo.png" alt="logo.png">
                        </div>
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center"><i class="feather icon-lock text-primary f-60 p-t-15 p-b-20 d-block"></i></h3>
                                    </div>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="email" id="email" name="email" class="form-control" required="" placeholder="Your Email Address">
                                    <span class="form-bar"></span>
                                </div>
                                <div class="row">
                                    <?php if ($lockUnlock): ?>
                                        <div class="col-md-12">
                                            <button id="lock-unlock" type="button" data-lock-unlock="<?= $lockUnlockValue ?>" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20"><i class="icofont icofont-lock"></i> UnLock Screen </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-md-12">
                                            <button id="lock-unlock" type="button" data-lock-unlock="<?= $lockUnlockValue ?>" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20"><i class="icofont icofont-lock"></i> Lock Screen </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <p class="text-inverse text-right">Back to <a href="index.php">Login</a></p>
                                
                            </div>
                        </div>
                    </form>
                    <!-- Login card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
    <!-- Required Jquery -->
    <script type="text/javascript" src=".\files\bower_components\jquery\js\jquery.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\jquery-ui\js\jquery-ui.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\popper.js\js\popper.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\bootstrap\js\bootstrap.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src=".\files\bower_components\jquery-slimscroll\js\jquery.slimscroll.js"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src=".\files\bower_components\modernizr\js\modernizr.js"></script>
    <script type="text/javascript" src=".\files\bower_components\modernizr\js\css-scrollbars.js"></script>
    <!-- i18next.min.js -->
    <script type="text/javascript" src=".\files\bower_components\i18next\js\i18next.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\i18next-xhr-backend\js\i18nextXHRBackend.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src=".\files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
    <!--Color Script Common-->
    <script type="text/javascript" src=".\files\assets\js\common-pages.js"></script>

    <script src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css"></script>


<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
    <script type="text/javascript">
        $('#lock-unlock').click(function(){
            (async () => {
                const { value: formValues } = await Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to perform this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                });

                const lockUnlock = $(this).data('lock-unlock');
                const enteredEmail = $('#email').val();
                console.log('id:', lockUnlock); 
                console.log('enteredEmail:', enteredEmail); 

                if (formValues) {
                    var data = {
                        lockUnlock: lockUnlock,
                        enteredEmail: enteredEmail,
                        action: "lock-unlock"
                    };
                    console.log('Data HERE: ' + JSON.stringify(data));
                    $.ajax({
                        url: 'lock_unlock.php',
                        type: 'post',
                        data: data,
                        success: function(response) {
                            const responseObject = JSON.parse(response);
                            console.log(`RESPONSE HERE: ${responseObject}`);
                            if (response && responseObject.status === 'success') {
                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    html: responseObject.message,
                                    confirmButtonColor: '#01a9ac',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        var lockedPage = '<?php echo isset($_SESSION["locked_page"]) ? $_SESSION["locked_page"] : "index.php"; ?>';
                                        window.location.href = lockedPage;
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
                            console.log("AJAX error:");
                            console.log("Status:", status);
                            console.log("Error:", error);
                            console.log("Response:", xhr.responseText);
                            Swal.fire('Error!', 'Failed to lock/unlock.', 'error');
                        }

                    });
                }
            })()
        })
    </script>
</body>

</html>
