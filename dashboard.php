<?php
include 'includes/connection.php';
include 'includes/chk_login.php';

$result        = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$row           = mysqli_fetch_assoc($result);
$totalStudents = $row["total"];

$pageTitle       = "Dashboard";
$activePage      = "dashboard";
$breadcrumbLabel = "Student Dashboard";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>

<body class="page-dashboard">
    <div class="bg_dashboard h-screen">
        <div class="row">

            <!--left menu-->
            <?php include 'sidebar.php'; ?>
            <!--left menu-->

            <div class="right_bar">

                <!--header-->
                <?php include 'header.php'; ?>
                <!--header-->

                <div class="row mt-4 max_565">

                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="std_list.php" class="col-span-12 text-white sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-3" style="background:#555684">
                                    <div class="d-flex">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </div>
                                    <div class="fs-2 font-medium leading-8 mt-3"><?php echo $totalStudents; ?></div>
                                    <div class="fs-6 mt-1">Total Students</div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>