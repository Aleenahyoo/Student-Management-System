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

$fullname       = $student["fullname"];
$username       = $student["username"];
$email          = $student["email"];
$phone          = $student["phone"];
$gender         = $student["gender"];
$dob            = $student["dob"];
$age            = $student["age"];
$about          = $student["about"];
$hobbies        = $student["hobbies"] != "" ? explode(", ", $student["hobbies"]) : array();
$qualifications = $student["qualifications"] != "" ? explode(", ", $student["qualifications"]) : array();
$oldPhoto       = $student["photo"];

$error          = "";

$genders                = array("Male", "Female", "Other");
$hobbyOptions           = array("Reading", "Sports", "Music", "Travel");
$qualificationOptions   = array("SSLC", "Plus Two", "Diploma", "Degree", "PG");

if (isset($_POST["update"])) 
{
    $fullname           = trim($_POST["fullname"] ?? "");
    $username           = trim($_POST["username"] ?? "");
    $email              = trim($_POST["email"] ?? "");
    $phone              = trim($_POST["phone"] ?? "");
    $gender             = trim($_POST["gender"] ?? "");
    $dob                = trim($_POST["dob"] ?? "");
    $age                = trim($_POST["age"] ?? "");
    $about              = trim($_POST["about"] ?? "");
    $hobbies            = $_POST["hobbies"] ?? array();
    $qualifications     = $_POST["qualifications"] ?? array();
    $password           = trim($_POST["password"] ?? "");
    $confirmPassword    = trim($_POST["confirm_password"] ?? "");

    if ($fullname == "" || $username == "" || $email == "") 
    {
        $error = "Full name, username and email are required.";
    } 
    elseif ($password != "" && strlen($password) < 6) 
    {
        $error = "Password must be at least 6 characters.";
    } 
    elseif ($password != "" && $password != $confirmPassword) 
    {
        $error = "Passwords do not match.";
    }

    if ($error == "") 
    {
        $checkSql = "SELECT id FROM users WHERE username = '$username' AND id != $id";
        $check = mysqli_query($conn, $checkSql);

        if ($check && mysqli_num_rows($check) > 0) 
        {
            $error = "That username is already taken.";
        }
    }

    if ($error == "") 
    {
        $checkSql = "SELECT id FROM users WHERE email = '$email' AND id != $id";
        $check = mysqli_query($conn, $checkSql);

        if ($check && mysqli_num_rows($check) > 0) 
        {
            $error = "That email is already used by another account.";
        }
    }

    $photo = $oldPhoto;

    if ($error == "" && ($_FILES["photo"]["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) 
    {
        $fileType = mime_content_type($_FILES["photo"]["tmp_name"]);
        $fileSize = $_FILES["photo"]["size"];

        if (!in_array($fileType, array("image/jpeg", "image/png", "image/webp", "image/gif"))) 
        {
            $error = "Profile picture must be JPG, PNG, WEBP or GIF.";
        } 
        elseif ($fileSize > 2 * 1024 * 1024) 
        {
            $error = "Profile picture must be smaller than 2MB.";
        } 
        else 
        {
            $extension   = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
            $photo       = "student_" . uniqid() . "." . $extension;

            if (!is_dir(__DIR__ . "/uploads")) 
            {
                mkdir(__DIR__ . "/uploads", 0755, true);
            }

            $uploadPath = __DIR__ . "/uploads/" . $photo;

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $uploadPath)) 
            {
                if ($oldPhoto != "" && file_exists(__DIR__ . "/uploads/" . $oldPhoto)) 
                {
                    unlink(__DIR__ . "/uploads/" . $oldPhoto);
                }
            } 
            else 
            {
                $error = "Could not upload the profile picture.";
                $photo = $oldPhoto;
            }
        }
    }

    if ($error == "") 
    {
        $hobbies_text       = implode(", ", $hobbies);
        $qualification_text = implode(", ", $qualifications);

        $saved = mysqli_query($conn, "UPDATE users SET fullname='$fullname', username='$username', email='$email', phone='$phone', gender='$gender', dob='$dob', age='$age', qualifications='$qualification_text', hobbies='$hobbies_text', about='$about', photo='$photo' WHERE id=$id");

        if ($saved && $password != "") 
        {
            $saved = mysqli_query($conn, "UPDATE users SET password='$password' WHERE id=$id");
        }

        if ($saved) 
        {
            header("Location: std_list.php?msg=updated");
            exit();
        } 
        else 
        {
            $error = "Error updating student.";
        }
    }
}

$pageTitle       = "Edit Student";
$activePage      = "students";
$breadcrumbLabel = "Edit Student";
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

<body class="page-edit-student">
    <div class="bg_dashboard h-screen">
        <div class="row">

            <!--left menu-->
            <?php include 'sidebar.php'; ?>
            <!--left menu-->

            <div class="right_bar">

                <!--header-->
                <?php include 'header.php'; ?>
                <!--header-->

                <div class="row mt-4">
                    <div class="grids gap-6 mt-1">
                        <div class="intro-y col-span-12 border-1 shadow-md">
                            <div class="intro-y box mt-3">
                                <div class="d-flex items-center mb-2 com_hd">
                                    <h2>Edit Student</h2>
                                </div>

                                <?php if ($error != "") { ?>
                                    <div class="alert alert-danger mx-3 mt-3"><?php echo htmlspecialchars($error); ?></div>
                                <?php } ?>

                                <form method="post" enctype="multipart/form-data" class="p-3">

                                    <div class="d-flex align-items-center justify-content-center w-full p-3">
                                        <label for="dropzone-file" class="file_uploader_bordr">
                                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                                <i class="fa-solid fa-cloud-arrow-up fs-2 mb-2"></i>
                                                <p class="mb-2"><span>Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500">JPG, PNG, WEBP or GIF (MAX. 2MB)</p>
                                            </div>
                                            <input id="dropzone-file" type="file" name="photo" accept="image/*" class="hidden">
                                        </label>
                                    </div>

                                    <?php if ($oldPhoto != "") { ?>
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <img src="uploads/<?php echo htmlspecialchars($oldPhoto); ?>" class="rounded-sm"
                                                style="width:56px;height:56px;border-radius:50%;object-fit:cover;">
                                            <span class="text-small">Current photo</span>
                                        </div>
                                    <?php } ?>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($fullname); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="tel" name="phone" maxlength="10" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($dob); ?>">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Age</label>
                                            <select name="age" class="form-control">
                                                <option value="">Select Age</option>
                                                <?php for ($i = 5; $i <= 35; $i++) { ?>
                                                    <option value="<?php echo $i; ?>" <?php echo $age == $i ? "selected" : ""; ?>><?php echo $i; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Gender</label>
                                            <?php foreach ($genders as $item) { ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="gender_<?php echo $item; ?>"
                                                        value="<?php echo $item; ?>" <?php echo $gender == $item ? "checked" : ""; ?>>
                                                    <label class="form-check-label" for="gender_<?php echo $item; ?>"><?php echo $item; ?></label>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Qualification</label>
                                            <select name="qualifications[]" class="form-control" multiple size="5">
                                                <?php foreach ($qualificationOptions as $item) { ?>
                                                    <option value="<?php echo $item; ?>" <?php echo in_array($item, $qualifications) ? "selected" : ""; ?>><?php echo $item; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label class="form-label">Hobbies</label>
                                            <div class="d-flex gap-3 flex-wrap">
                                                <?php foreach ($hobbyOptions as $item) { ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="hobbies[]" id="hobby_<?php echo $item; ?>"
                                                            value="<?php echo $item; ?>" <?php echo in_array($item, $hobbies) ? "checked" : ""; ?>>
                                                        <label class="form-check-label" for="hobby_<?php echo $item; ?>"><?php echo $item; ?></label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label class="form-label">About</label>
                                            <textarea name="about" class="form-control" rows="3"><?php echo htmlspecialchars($about); ?></textarea>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Leave blank to keep current">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password">
                                        </div>
                                    </div>

                                    <button type="submit" name="update" class="btn btn-primary mt-4">Save Changes</button>
                                    <a href="std_list.php" class="btn btn-warning mt-4">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /right_bar -->
        </div><!-- /row -->
    </div><!-- /bg_dashboard -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>