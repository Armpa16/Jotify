<?php
session_start();

// ตรวจสอบเข้าระบบ
if (isset($_SESSION['name'])) {
    $email = $_SESSION['email'];
    $pic = $_SESSION['user_pic'];

} else {
    header("Location: loginform.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Success</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <img src="<?php echo htmlspecialchars($pic); ?>" alt="Profile Picture" width="150" height="150">
    <p>You have successfully logged in.</p>
    <form method="POST" action="loginform.php">
        <button type="submit">Logout</button>
    </form>
</body>
</html>

