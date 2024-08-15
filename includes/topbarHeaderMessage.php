<ul class="nav-right">
    <li class="header-notification">
        <div class="dropdown-primary dropdown">
            <div class="dropdown-toggle" data-toggle="dropdown">
                <i class="feather icon-bell"></i>
                <span class="badge bg-c-pink">5</span>
            </div>
            <ul class="show-notification notification-view dropdown-menu"
                data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                <li>
                    <h6>Notifications</h6>
                    <label class="label label-danger">New</label>
                </li>
                <li>
                    <div class="media">
                        <img class="d-flex align-self-center img-radius"
                            src="..\files\assets\images\avatar-4.jpg"
                            alt="Generic placeholder image">
                        <div class="media-body">
                            <h5 class="notification-user">John Doe</h5>
                            <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer
                                elit.</p>
                            <span class="notification-time">30 minutes ago</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="media">
                        <img class="d-flex align-self-center img-radius"
                            src="..\files\assets\images\avatar-3.jpg"
                            alt="Generic placeholder image">
                        <div class="media-body">
                            <h5 class="notification-user">Joseph William</h5>
                            <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer
                                elit.</p>
                            <span class="notification-time">30 minutes ago</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="media">
                        <img class="d-flex align-self-center img-radius"
                            src="..\files\assets\images\avatar-4.jpg"
                            alt="Generic placeholder image">
                        <div class="media-body">
                            <h5 class="notification-user">Sara Soudein</h5>
                            <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer
                                elit.</p>
                            <span class="notification-time">30 minutes ago</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </li>
    <li class="user-profile header-notification">
        <div class="dropdown-primary dropdown">
            <div class="dropdown-toggle" data-toggle="dropdown">
                <?php
                    $image_src = !empty($session_image) ? $session_image : '..\files\assets\images\avatar-4.jpg';
                    echo '<img src="' . $image_src . '" class="img-radius" alt="User-Profile-Image">';
                ?>
                <span><?php echo $session_sfirstname . ' ' . $session_smiddlename . ' ' . $session_slastname; ?></span>
                <i class="feather icon-chevron-down"></i>
            </div>
            <ul class="show-notification profile-notification dropdown-menu"
                data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                <li>
                    <a href="staff_detailed.php?id=<?= $session_id ?>&view=2">
                        <i class="feather icon-user"></i> Profile
                    </a>
                </li>
                <!-- <li>
                    <a href="../lock_screen.php">
                        <i class="feather icon-lock"></i> Lock Screen
                    </a>
                </li> -->
                <li>
                    <a href="../logout.php">
                        <i class="feather icon-log-out"></i> Logout
                    </a>
                </li>
            </ul>

        </div>
    </li>
</ul>