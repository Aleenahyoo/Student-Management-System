<?php
include 'includes/connection.php';
include 'includes/chk_login.php';

$message = "";
$error   = "";

if (($_GET["msg"] ?? "") !== "") 
{
    if ($_GET["msg"] == "added") 
    {
        $message = "Student added successfully.";
    }
    if ($_GET["msg"] == "updated") 
    {
        $message = "Student updated successfully.";
    }
}

if (($_GET["delete"] ?? "") !== "") 
{
    $id = (int) $_GET["delete"];

    $result = mysqli_query($conn, "SELECT username, photo FROM users WHERE id = $id");
    $student = $result ? mysqli_fetch_assoc($result) : false;

    if (!$student) 
    {
        $error = "Student not found.";
    } 
    elseif ($student["username"] == $_SESSION["username"]) 
    {
        $error = "You cannot delete your own account.";
    } 
    else 
    {
        if ($student["photo"] != "") 
        {
            $file = __DIR__ . "/uploads/" . $student["photo"];
            if (file_exists($file)) 
            {
                unlink($file);
            }
        }

        if (mysqli_query($conn, "DELETE FROM users WHERE id = $id")) 
        {
            $message = "Student deleted successfully.";
        } 
        else 
        {
            $error = "Error deleting student.";
        }
    }
}

$name  = isset($_GET["name"]) ? trim($_GET["name"]) : "";
$email = isset($_GET["email"]) ? trim($_GET["email"]) : "";
$phone = isset($_GET["phone"]) ? trim($_GET["phone"]) : "";

$where = "";

if ($name !== "") 
{
    $where .= " AND fullname LIKE '%" . trim($name) . "%'";
}
if ($email !== "") 
{
    $where .= " AND email = '" . trim($email) . "'";
}
if ($phone !== "") 
{
    $where .= " AND phone = '" . trim($phone) . "'";
}

$sql = "SELECT * FROM users WHERE 1=1 " . $where . " ORDER BY id DESC";

$students = mysqli_query($conn, $sql);

$pageTitle       = "Manage Students";
$activePage      = "students";
$breadcrumbLabel = "Manage Students";
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

            <!--left menu-->
            <?php include 'sidebar.php'; ?>
            <!--left menu-->

            <div class="right_bar">

                <!--header-->
                <?php include 'header.php'; ?>
                <!--header-->

                <div class="row mt-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                        <form method="get" action="std_list.php" class="d-flex gap-2 align-items-end flex-wrap flex-grow-1">
                            <div class="input-group" style="max-width: 220px;">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Name"
                                    value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="input-group" style="max-width: 260px;">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="Email"
                                    value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="input-group" style="max-width: 220px;">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-phone text-muted"></i></span>
                                <input type="text" name="phone" class="form-control" placeholder="Phone"
                                    value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary shadow-md">
                                Search
                            </button>
                            <?php if ($name !== "" || $email !== "" || $phone !== "") { ?>
                                <a href="std_list.php" class="btn btn-outline-secondary" title="Clear Search">
                                    <i class="fa-solid fa-xmark"></i> Clear
                                </a>
                            <?php } ?>
                        </form>
                    </div>

                    <div class="grids gap-6 mt-1">
                        <div class="intro-y col-span-12 border-1 shadow-md">
                            <div class="intro-y box mt-3 p-4">
                                <div class="d-flex items-center mb-3 com_hd">
                                    <h2>List Students</h2>
                                </div>

                                <?php if ($message != "") { ?>
                                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                                <?php } ?>
                                <?php if ($error != "") { ?>
                                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                                <?php } ?>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Full Name</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Joined</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($students && mysqli_num_rows($students) > 0) { ?>
                                                <?php while ($student = mysqli_fetch_assoc($students)) { ?>
                                                    <?php
                                                    if ($student["photo"] != "") 
                                                    {
                                                        $photo = "uploads/" . $student["photo"];
                                                    } 
                                                    else 
                                                    {
                                                        $photo = "https://ui-avatars.com/api/?background=1e40af&color=fff&name="
                                                            . urlencode($student["fullname"]);
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><img src="<?php echo htmlspecialchars($photo); ?>"
                                                                style="width:36px;height:36px;border-radius:50%;object-fit:cover;" alt=""></td>
                                                        <td><?php echo htmlspecialchars($student["fullname"]); ?></td>
                                                        <td><?php echo htmlspecialchars($student["username"]); ?></td>
                                                        <td><?php echo htmlspecialchars($student["email"]); ?></td>
                                                        <td><?php echo $student["phone"] != "" ? htmlspecialchars($student["phone"]) : "-"; ?></td>
                                                        <td><?php echo $student["gender"] != "" ? htmlspecialchars($student["gender"]) : "-"; ?></td>
                                                        <td><?php echo $student["age"] != "" ? htmlspecialchars($student["age"]) : "-"; ?></td>
                                                        <td><?php echo $student["created_at"] != "" ? date("d M Y", strtotime($student["created_at"])) : "-"; ?></td>
                                                        <td>
                                                            <div class="d-flex items-center gap-2">
                                                                <a href="std_view.php?id=<?php echo $student["id"]; ?>" class="btn btn-success shadow-md btn-sm" title="View">
                                                                    <i class="fa-solid fa-eye"></i>
                                                                </a>
                                                                <a href="std_edit.php?id=<?php echo $student["id"]; ?>" class="btn btn-primary shadow-md btn-sm" title="Edit">
                                                                    <i class="fa-solid fa-pen"></i>
                                                                </a>
                                                                <a href="std_list.php?delete=<?php echo $student["id"]; ?>" class="btn btn-danger shadow-md btn-sm" title="Delete"
                                                                    onclick="return confirm('Delete this student?');">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">No students found.</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

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
