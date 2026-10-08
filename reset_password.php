<?php
include 'includes/connection.php';
include 'includes/chk_login.php';

$username = trim($_SESSION["username"] ?? "");
$error    = "";
$message  = "";

if (isset($_POST["reset_password"])) 
{
    $currentPassword = trim($_POST["current_password"] ?? "");
    $newPassword     = trim($_POST["new_password"] ?? "");
    $confirmPassword = trim($_POST["confirm_password"] ?? "");

    if ($currentPassword === "" || $newPassword === "" || $confirmPassword === "") 
    {
        $error = "Please fill in all password fields.";
    } 
    elseif (strlen($newPassword) < 6) 
    {
        $error = "New password must be at least 6 characters.";
    } 
    elseif ($newPassword !== $confirmPassword) 
    {
        $error = "New password and confirmation do not match.";
    } 
    else 
    {
        $safeUser = trim($username);
        $result   = mysqli_query($conn, "SELECT password FROM users WHERE username = '$safeUser' LIMIT 1");
        $user     = $result ? mysqli_fetch_assoc($result) : false;

        if (!$user || $user["password"] !== $currentPassword) 
        {
            $error = "Current password is incorrect.";
        } 
        elseif ($newPassword === $currentPassword) 
        {
            $error = "Choose a new password that is different from your current one.";
        } 
        else 
        {
            $safeNew = trim($newPassword);
            if (mysqli_query($conn, "UPDATE users SET password = '$safeNew' WHERE username = '$safeUser'")) 
            {
                $message = "Password updated successfully.";
            } 
            else 
            {
                $error = "Could not update password. Please try again.";
            }
        }
    }
}

$pageTitle       = "Reset Password";
$activePage      = "dashboard";
$breadcrumbLabel = "Reset Password";
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

            <?php include 'sidebar.php'; ?>

            <div class="right_bar">

                <?php include 'header.php'; ?>

                <div class="row mt-4">
                    <div class="grids gap-6 mt-1">
                        <div class="intro-y col-span-12 border-1 shadow-md">
                            <div class="intro-y box mt-3 p-4">
                                <div class="d-flex items-center mb-3 com_hd">
                                    <h2>Reset Password</h2>
                                </div>

                                <?php if ($message !== "") { ?>
                                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                                <?php } ?>
                                <?php if ($error !== "") { ?>
                                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                                <?php } ?>

                                <form method="post" action="reset_password.php" class="max_565">
                                    <div class="mb-3">
                                        <label class="form-label">Current password</label>
                                        <input type="password" name="current_password" class="form-control" autocomplete="current-password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">New password</label>
                                        <input type="password" name="new_password" class="form-control" autocomplete="new-password" minlength="6" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm new password</label>
                                        <input type="password" name="confirm_password" class="form-control" autocomplete="new-password" minlength="6" required>
                                    </div>
                                    <button type="submit" name="reset_password" class="btn btn-primary shadow-md">Update password</button>
                                    <a href="dashboard.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                                </form>
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