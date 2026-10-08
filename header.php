<?php

$pageTitle       = $pageTitle ?? "Students Portal";
$activePage      = $activePage ?? "";
$breadcrumbLabel = $breadcrumbLabel ?? $pageTitle;

$headerUsername = $_SESSION["username"] ?? "";
$headerResult   = mysqli_query($conn, "SELECT fullname, photo FROM users WHERE username = '" . trim($headerUsername) . "'");
$headerUser     = $headerResult ? mysqli_fetch_assoc($headerResult) : null;

$headerFullname = $headerUser["fullname"] ?? "User";

if ($headerUser && $headerUser["photo"] != "") 
{
    $headerPhoto = "uploads/" . $headerUser["photo"];
} 
else 
{
    $headerPhoto = "https://ui-avatars.com/api/?background=1e40af&color=fff&name=" . urlencode($headerFullname);
}
?>

<div class="top_bar d-flex justify-content-between align-items-center">

    <ol class="breadcrumb mt-2 mb-3">
        <li class="breadcrumb-item">
            <a href="dashboard.php">Students Portal</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?php echo htmlspecialchars($breadcrumbLabel); ?>
        </li>
    </ol>

    <div class="user onhover-dropdown">
        <span><img src="<?php echo htmlspecialchars($headerPhoto); ?>" alt="" /></span>

        <div class="onhover-div onhover-div-login">
            <ul class="user-box-name">

                <li class="fst_drp_li">
                    <div class="font-medium"><?php echo htmlspecialchars($headerFullname); ?></div>
                    <div class="text-small">Students Portal</div>
                </li>

                <li class="product-box-contain">
                    <a href="reset_password.php">Reset password</a>
                </li>
                
                <li class="product-box-contain">
                    <a href="logout.php">Log out</a>
                </li>
            </ul>
        </div>

    </div>
</div>