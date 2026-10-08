<?php
$liDashboard = "";
$liStudents  = "";
$liLogout    = "";

if ($activePage == "dashboard") 
{
    $liDashboard = " active_mnu";
}
if ($activePage == "students") 
{
    $liStudents  = " active_mnu";
}
if ($activePage == "logout") 
{
    $liLogout    = " active_mnu";
}
?>

<div class="left_bar">
    <div class="logo_dash">
        <a href="dashboard.php" class="intro-x d-flex gap-2 align-items-center ps-3">
            <span class="text-white text-lg ml-3">Students Portal</span>
        </a>
    </div>
    <div class="navigation_sec mt-4">
        <ul>
            <li class="<?php echo $liDashboard; ?>">
                <a href="dashboard.php">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="hd_tb">Dashboard</span>
                </a>
            </li>
            <li class="<?php echo $liStudents; ?>">
                <a href="std_list.php">
                    <i class="fa-solid fa-users"></i>
                    <span class="hd_tb">Student List</span>
                </a>
            </li>
            <li class="<?php echo $liLogout; ?>">
                <a href="logout.php">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="hd_tb">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>