<nav class="pcoded-navbar">
    <div class="pcoded-inner-navbar main-menu">
        <?php if ($session_role == 'Manager'  || $session_role == 'Admin') : ?>
            <div class="pcoded-navigatio-lavel">Navigation</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo ($page_name == 'dashboard') ? 'active' : ''; ?>">
                    <a href="index.php">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>
            </ul>
            <div class="pcoded-navigatio-lavel">Appplications</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="<?php echo ($page_name == 'department') ? 'active' : ''; ?>">
                    <a href="department.php">
                        <span class="pcoded-micon"><i class="feather icon-monitor"></i></span>
                        <span class="pcoded-mtext">Department</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'staff' || $page_name == 'new_staff' || $page_name == 'staff_list') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Staff</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'new_staff') ? 'active' : ''; ?>">
                            <a href="new_staff.php">
                                <span class="pcoded-mtext">New Staff</span>
                            </a>
                        </li>
                        <li class="<?php echo ($page_name == 'staff_list') ? 'active' : ''; ?>">
                            <a href="staff_list.php">
                                <span class="pcoded-mtext">Manage Staff</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="<?php echo ($page_name == 'leave_type') ? 'active' : ''; ?>">
                    <a href="leave_type.php">
                        <span class="pcoded-micon"><i class="feather icon-shuffle"></i></span>
                        <span class="pcoded-mtext">Leave Type</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'leave' || $page_name == 'apply_leave' || $page_name == 'leave_request') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-shuffle"></i></span>
                        <span class="pcoded-mtext">Leave</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <?php if ($session_role == 'Manager' || $session_role == 'Admin' || $session_role == 'Staff') : ?>
                            <li class="<?php echo ($page_name == 'apply_leave') ? 'active' : ''; ?>">
                                <a href="apply_leave.php">
                                    <span class="pcoded-mtext">Apply Leave</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="<?php echo ($page_name == 'leave_request') ? 'active' : ''; ?>">
                            <a href="leave_request.php?leave_status=0">
                                <span class="pcoded-mtext">All Leaves</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="pcoded-hasmenu <?php echo ($page_name == 'customer' || $page_name == 'new_customer' || $page_name == 'customer_list') ? 'active pcoded-trigger' : ''; ?>">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                        <span class="pcoded-mtext">Customer</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="<?php echo ($page_name == 'new_customer') ? 'active' : ''; ?>">
                            <a href="new_customer.php">
                                <span class="pcoded-mtext">New Customer</span>
                            </a>
                        </li>
                        <li class="<?php echo ($page_name == 'customer_list') ? 'active' : ''; ?>">
                            <a href="customer_list.php">
                                <span class="pcoded-mtext">Customer List</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php endif; ?>    
        <div class="pcoded-navigatio-lavel">Support</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="">
                <a href="https://www.youtube.com/@codelytical" target="_blank">
                    <span class="pcoded-micon"><i class="feather icon-monitor"></i></span>
                    <span class="pcoded-mtext">Contact Us</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
