<?php

include 'includes/connection.php';

$username = "";
$errorMsg = "";

if (isset($_POST["login"])) 
{
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === "" || $password === "") 
    {
        $errorMsg = "Please enter username and password.";
    } 
    else 
    {
        $sql = "SELECT username, password FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) 
        {
            $row = mysqli_fetch_assoc($result);

            $_SESSION["username"] = $row["username"];
            header("Location: dashboard.php");
            exit();
        } 
        else 
        {
            $errorMsg = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Creative Youth | Students Portal Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>

<body>

    <div class="main_sec">
        <div class="form_grp_lgn">
            <div class="logo">
                <a href="login.php">Students Portal</a>
            </div>

            <h3>Sign In</h3>
            <p>Manage all your accounts in one place</p>

            <?php if (($_GET["registered"] ?? "") !== "") { ?>
                <div class="alert alert-success w-100">Account created. You can log in now.</div>
            <?php } ?>

            <?php if ($errorMsg !== "") { ?>
                <div class="alert_msg"><?php echo htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } ?>

            <form method="post" action="login.php" style="width: 100%;">
                <div class="input_frm_lgn">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="username" placeholder="Username"
                        value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="input_frm_lgn">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Password">
                </div>

                <button type="submit" name="login" class="comn_btn_2">Login <i class="fa-solid fa-arrow-right-long ms-2"></i></button>
            </form>

            <p class="mt-3 mb-0 text-center">
                Don't have an account? <a href="register.php" style="color:#1e40af; font-weight:600;">Register</a>
            </p>
        </div>
    </div>

</body>

</html>