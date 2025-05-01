<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$dbname = "todolist";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Retrieve and sanitize user inputs
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

// Query database for user
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch user data
    $user = $result->fetch_assoc();
    $hashed_password = $user['password_hash']; // Password from database
    
    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Password matches, login successful
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        header("Location: /Code_Aj_Lak/home.php"); // Redirect to success page
        exit();
    } else {
        // Password incorrect
        header("Location: /Code_Aj_Lak/loginform.php?status=2");
        exit();
    }
} else {
    // Email not found
    header("Location: /Code_Aj_Lak/loginform.php?status=1");
    exit();
}

$conn->close();
?>
