<?php
include 'includes/connection.php';

$fullname       = "";
$username       = "";
$email          = "";
$gender         = "";
$dob            = "";
$phone          = "";
$age            = "";
$about          = "";
$hobbies        = [];
$qualifications = [];
$filename       = "";
$errorMsg       = "";

if (isset($_POST["register"])) 
{
    $fullname           = trim($_POST["fullname"]);
    $username           = trim($_POST["username"]);
    $email              = trim($_POST["email"]);
    $password           = trim($_POST["password"]);
    $gender             = trim($_POST["gender"]);
    $phone              = trim($_POST["phone"]);
    $dob                = trim($_POST["dob"]);
    $age                = trim($_POST["age"]);
    $hobbies            = $_POST["hobbies"];
    $qualifications     = $_POST["qualifications"];
    $about              = trim($_POST["about"]);

    if ($fullname == "" || $username == "" || $email == "" || $password == "") 
    {
        $errorMsg = "Please fill in all required fields.";
    } 
    elseif (strlen($password) < 6) 
    {
        $errorMsg = "Password must be at least 6 characters.";
    } 
    elseif ($gender == "") 
    {
        $errorMsg = "Please select a gender.";
    } 
    elseif ($phone == "" || !preg_match("/^[0-9]{10}$/", $phone)) 
    {
        $errorMsg = "Please enter a valid 10-digit phone number.";
    } 
    elseif ($dob == "") 
    {
        $errorMsg = "Please enter your date of birth.";
    }
    elseif ($age == "") 
    {
        $errorMsg = "Please select your age.";
    } 
    elseif (empty($qualifications)) 
    {
        $errorMsg = "Please select a qualification.";
    } 
    elseif ($about == "") 
    {
        $errorMsg = "Please tell us something about yourself.";
    } 
    elseif (($_FILES["photo"]["error"] ?? UPLOAD_ERR_NO_FILE) == UPLOAD_ERR_NO_FILE) 
    {
        $errorMsg = "Please select a photo.";
    } 
    else 
    {
        $checkSql    = "SELECT id FROM users WHERE username = '$username'";
        $checkResult = mysqli_query($conn, $checkSql);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) 
        {
            $errorMsg = "Username already exists.";
        } 
        else 
        {
            $target_dir = __DIR__ . "/uploads/";

            if (!is_dir($target_dir)) 
            {
                mkdir($target_dir, 0755, true);
            }

            $extension   = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
            $filename    = "student_" . uniqid() . "." . $extension;
            $target_file = $target_dir . $filename;

            if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) 
            {
                $errorMsg = "Error uploading photo.";
            } 
            else 
            {
                $hobbies_text       = implode(", ", $hobbies);
                $qualification_text = implode(", ", $qualifications);

                $sql = "INSERT INTO users (fullname, username, email, phone, gender, hobbies, age, qualifications, password, dob, about, photo) 
                        VALUES ('$fullname', '$username', '$email', '$phone', '$gender', '$hobbies_text', '$age', '$qualification_text', '$password', '$dob', '$about', '$filename')";

                if (mysqli_query($conn, $sql)) 
                {
                    header("Location: login.php?registered=1");
                    exit();
                } 
                else 
                {
                    $errorMsg = "Error: " . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Students Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/register.css">
</head>

<body>

    <div class="form_page_wrap">
        <div class="form_card">

            <h3>Create Account</h3>
            <p>Fill in the details below to register</p>

            <?php if ($errorMsg != "") { ?>
                <div class="form_alert form_alert_error"><?php echo htmlspecialchars($errorMsg); ?></div>
            <?php } ?>

            <form method="post" action="register.php" enctype="multipart/form-data">
                <div class="form_row">
                    <div class="form_grp">
                        <label>Full Name</label>
                        <input type="text" name="fullname" placeholder="Enter full name"
                            value="<?php echo htmlspecialchars($fullname); ?>">
                    </div>
                    <div class="form_grp">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter username"
                            value="<?php echo htmlspecialchars($username); ?>">
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter email" value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                    <div class="form_grp">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" maxlength="10" placeholder="Enter phone number"
                            value="<?php echo htmlspecialchars($phone); ?>">
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter password">
                    </div>
                    <div class="form_grp">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp">
                        <label>Gender</label>
                        <div class="form_radio_group">
                            <label class="form_radio_item">
                                <input type="radio" name="gender" value="Male" <?php if ($gender == "Male") echo "checked"; ?>>
                                Male
                            </label>
                            <label class="form_radio_item">
                                <input type="radio" name="gender" value="Female" <?php if ($gender == "Female") echo "checked"; ?>>
                                Female
                            </label>
                            <label class="form_radio_item">
                                <input type="radio" name="gender" value="Other" <?php if ($gender == "Other") echo "checked"; ?>>
                                Other
                            </label>
                        </div>
                    </div>
                    <div class="form_grp">
                        <label>Age</label>
                        <select name="age">
                            <option value="">Select Age</option>
                            <?php for ($i = 5; $i <= 35; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php echo $age == $i ? "selected" : ""; ?>><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp form_grp_full">
                        <label>Hobbies</label>
                        <div class="form_checkbox_group">
                            <?php foreach (["Reading", "Sports", "Music", "Travel"] as $h) { ?>
                                <label class="form_checkbox_item">
                                    <input type="checkbox" name="hobbies[]" value="<?php echo $h; ?>" <?php echo in_array($h, $hobbies) ? "checked" : ""; ?>>
                                    <?php echo $h; ?>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp form_grp_full">
                        <label>Qualification</label>
                        <select name="qualifications[]" size="5" multiple>
                            <?php foreach (["SSLC", "Plus Two", "Diploma", "Degree", "PG"] as $q) { ?>
                                <option value="<?php echo $q; ?>" <?php echo in_array($q, $qualifications) ? "selected" : ""; ?>><?php echo $q; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp form_grp_full">
                        <label>Photo</label>
                        <input type="file" name="photo" accept="image/*">
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp form_grp_full">
                        <label>About</label>
                        <textarea name="about" rows="3"
                            placeholder="Tell us a little about yourself"><?php echo htmlspecialchars($about); ?></textarea>
                    </div>
                </div>

                <button type="submit" name="register" class="btn_update">Register</button>
            </form>

            <p class="form_footer_link">
                Already have an account? <a href="login.php">Login here</a>
            </p>

        </div>
    </div>

</body>

</html>