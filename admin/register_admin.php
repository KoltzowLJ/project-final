<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:admin_login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Initialize the $message array
$message = [];

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);


    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
    $select_admin->execute([$name]);


    if ($select_admin->rowCount() > 0) {
        $message[] = 'Username already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password does not match!';
        } else {
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            $insert_admin = $conn->prepare("INSERT INTO `admins`(name, password) VALUES(?,?)");
            $insert_admin->execute([$name, $hashed_password]);
            $message[] = 'New admin registered successfully!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WJVLZYDW1W"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-WJVLZYDW1W');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">
    <form action="" method="post">
        <h3>Register Now</h3>
        <?php
        if (!empty($message) && is_array($message)) {
            foreach ($message as $msg) {
                echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
            }
        }
        ?>
        <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box">
        <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box">
        <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20" class="box">
        <input type="submit" value="Register Now" class="btn" name="submit">
    </form>
</section>

<script src="../assets/js/admin_script.js"></script>

<script>
document.getElementById('user-btn').addEventListener('click', function() {
    document.querySelector('.profile').classList.toggle('active');
});
</script>

</body>
</html>