<!DOCTYPE html>
<html lang="en">

<head>
    <title>Leave Portal </title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Favicon icon -->
    <link rel="icon" href=".\files\assets\images\favicon.ico" type="image/x-icon">
    <!-- Google font--><link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href=".\files\bower_components\bootstrap\css\bootstrap.min.css">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href=".\files\assets\icon\themify-icons\themify-icons.css">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href=".\files\assets\icon\icofont\css\icofont.css">
    <!-- feather Awesome -->
    <link rel="stylesheet" type="text/css" href=".\files\assets\icon\feather\css\feather.css">
    <!-- radial chart -->
    <link rel="stylesheet" href=".\files\assets\pages\chart\radial\css\radial.css" type="text/css" media="all">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href=".\files\assets\css\style.css">
    <link rel="stylesheet" type="text/css" href=".\files\assets\css\jquery.mCustomScrollbar.css">

    <script src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css"></script>

</head>

<?php
session_start();

// Destroy all session variables
session_unset();
session_destroy();

?>

<body class="fix-menu">
    <!-- Pre-loader start -->
    <?php include('includes/loader.php')?>
    <!-- Pre-loader end -->

    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->

                        <div class="md-float-material form-material">
                            <div class="auth-box card">
                                <div class="card-block">
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <h3 class="text-center">Leave Management Portal</h3>
                                        </div>
                                    </div>
                                    <div class="form-group form-primary">
                                        <input type="email" id="email" name="email" class="form-control email" required="" placeholder="Your Email Address">
                                        <span class="form-bar"></span>
                                    </div>
                                    <div class="form-group form-primary">
                                        <input type="password" id="password" name="password" class="form-control password" required="" placeholder="Password">
                                        <span class="form-bar"></span>
                                    </div>
                                    <div class="row m-t-25 text-left">
                                        <div class="col-12">
                                            <div class="forgot-phone text-right f-right">
                                                <a href="auth-reset-password.htm" class="text-right f-w-600"> Forgot Password?</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-t-30">
                                        <div class="col-md-12">
                                            <button type="submit" id="login-form" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">Sign in</button>
                                        </div>
                                    </div>
                                    <hr>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- end of form -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
            <div id="alert-container"></div>
        </div>
        <!-- end of container-fluid -->
    </section>
  
    <!-- Warning Section Ends -->
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
    <script type="text/javascript" src=".\files\assets\js\common-pages.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>

<script type="text/javascript">
    $('#login-form').click(function(event){
        event.preventDefault(); // prevent the default form submission
       
        (async () => {
            var data = {
                email: $('.email').val(),
                password: $('.password').val(), 
                action: "save",
            };
            if (data.email.trim() === '' || data.password.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please all fields are required. Kindly fill all',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
                return;
            }
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(data.email)) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please enter a valid email address.',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK'
                });
                return;
            }
            $.ajax({
                url: 'login.php',
                type: 'post',
                data: data,
                dataType: 'json',
                success:function(response){
                    console.log(response.message);
                    console.log("response user_type: " + response.role);
                    if (response.status == 'success') {
                        let titleMessage = response.message + " as " + response.role;
                        if (response.password_reset == false) {
                            titleMessage = "Please reset your password to proceed.";
                        }
                        Swal.fire({
                            icon: 'success',
                            title: titleMessage,
                            confirmButtonColor: '#01a9ac',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('.md-close').trigger('click'); // close the modal form
                                if (response.password_reset == false) {
                                    window.location = 'reset_password.php';
                                } else {
                                    if (response.role == 'admin') {
                                        window.location = 'admin/index.php';
                                    } else if (response.role == 'manager') {
                                        window.location = 'admin/index.php';
                                    } else if (response.role == 'staff') {
                                        window.location = 'staff/index.php';
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            text: 'Invalid user type or error',
                                            confirmButtonColor: '#eb3422',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                }
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
                    Swal.fire({
                            icon: 'error',
                            text: 'Internal Server Error: ' + textStatus,
                            confirmButtonColor: '#eb3422',
                            confirmButtonText: 'OK'
                        });
                }
            });
        })()
    })
</script>

</body>

</html>
