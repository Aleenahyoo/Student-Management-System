<?php

include 'includes/connection.php';
include 'includes/chk_login.php';

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) 
{
    header("Location: std_list.php");
    exit();
}

$result  = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$student = $result ? mysqli_fetch_assoc($result) : false;

if (!$student) 
{
    header("Location: std_list.php");
    exit();
}

if ($student["photo"] != "") 
{
    $photo = "uploads/" . $student["photo"];
} 

else 
{
    $photo = "https://ui-avatars.com/api/?background=1e40af&color=fff&name=" . urlencode($student["fullname"]);
}

$hobbies        = $student["hobbies"] != "" ? explode(", ", $student["hobbies"]) : array();
$qualifications = $student["qualifications"] != "" ? explode(", ", $student["qualifications"]) : array();

$dobFormatted       = $student["dob"] != "" ? date("d M Y", strtotime($student["dob"])) : "-";
$joinedFormatted    = $student["created_at"] != "" ? date("d M Y, h:i A", strtotime($student["created_at"])) : "-";

$pageTitle       = "View Student";
$activePage      = "students";
$breadcrumbLabel = "View Student";
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

<body class="page-students">
    <div class="bg_dashboard h-screen">
        <div class="row">

            <?php include 'sidebar.php'; ?>
            <div class="right_bar">

                <?php include 'header.php'; ?>
                <div class="row mt-4">

                    <div class="col-12 client-view-page">
                        <div class="client-view-top-actions">
                            <a href="std_list.php" class="client-view-back-btn">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                        </div>

                        <div class="client-view-card">
                            <div class="client-view-summary-grid">
                                <div class="client-view-summary-col">
                                    <div class="client-view-profile">
                                        <img src="<?php echo htmlspecialchars($photo); ?>" alt="">
                                        <div>
                                            <h1 class="client-view-profile-name"><?php echo htmlspecialchars($student["fullname"]); ?></h1>
                                            <p class="client-view-profile-meta">ID : <?php echo (int) $student["id"]; ?></p>
                                            <p class="client-view-profile-meta">Age : <?php echo $student["age"] != "" ? htmlspecialchars($student["age"]) : "-"; ?></p>
                                            <p class="client-view-profile-meta">Gender : <?php echo $student["gender"] != "" ? htmlspecialchars($student["gender"]) : "-"; ?></p>
                                            <span class="client-view-status-badge">Registered Student</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="client-view-summary-col">
                                    <h2 class="client-view-card-title">Contact Details</h2>
                                    <div class="client-view-contact-list">
                                        <p>
                                            <i class="fa-solid fa-envelope"></i>
                                            <?php echo htmlspecialchars($student["email"]); ?>
                                        </p>
                                        <p>
                                            <i class="fa-solid fa-phone"></i>
                                            <?php echo $student["phone"] != "" ? htmlspecialchars($student["phone"]) : "-"; ?>
                                        </p>
                                        <p>
                                            <i class="fa-brands fa-whatsapp"></i>
                                            <?php echo $student["phone"] != "" ? htmlspecialchars($student["phone"]) . " (whatsapp)" : "-"; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="client-view-summary-col">
                                    <h2 class="client-view-card-title">Details</h2>
                                    <div class="client-view-details-list">
                                        <p>DOB <span class="detail-arrow">→</span> <?php echo htmlspecialchars($dobFormatted); ?></p>
                                        <p>Username <span class="detail-arrow">→</span> <?php echo htmlspecialchars($student["username"]); ?></p>
                                        <p>Joined <span class="detail-arrow">→</span> <?php echo htmlspecialchars($joinedFormatted); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="client-view-two-col">
                            <div class="client-view-card mb-0">
                                <h2 class="client-view-card-title">Qualifications</h2>
                                <div class="client-view-row-list">
                                    <?php if (count($qualifications) > 0) { ?>
                                        <?php foreach ($qualifications as $i => $item) { ?>
                                            <div class="client-view-row-item">
                                                <span class="row-label">Qualification <?php echo $i + 1; ?></span>
                                                <span class="row-value"><span class="row-sep">:</span> <?php echo htmlspecialchars($item); ?></span>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="client-view-row-item">
                                            <span class="row-label">Qualification</span>
                                            <span class="row-value"><span class="row-sep">:</span> -</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="client-view-card mb-0">
                                <h2 class="client-view-card-title">Hobbies</h2>
                                <div class="client-view-row-list">
                                    <?php if (count($hobbies) > 0) { ?>
                                        <?php foreach ($hobbies as $i => $item) { ?>
                                            <div class="client-view-row-item">
                                                <span class="row-label">Hobby <?php echo $i + 1; ?></span>
                                                <span class="row-value"><span class="row-sep">:</span> <?php echo htmlspecialchars($item); ?></span>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="client-view-row-item">
                                            <span class="row-label">Hobby</span>
                                            <span class="row-value"><span class="row-sep">:</span> -</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="client-view-card client-view-about-card">
                            <h2 class="client-view-card-title">About</h2>
                            <div class="client-view-about-body">
                                <p class="client-view-about-text<?php echo $student["about"] == "" ? " is-empty" : ""; ?>">
                                    <?php echo $student["about"] != "" ? nl2br(htmlspecialchars($student["about"])) : "No details added."; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>