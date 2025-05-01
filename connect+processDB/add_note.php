<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root"; 
$password = "";   
$dbname = "todolist";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // เรียกใช้ session
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$users_id = $_SESSION['user_id']; // ดึง user_id จาก session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $note = $_POST['takenote'];
    $color = $_POST['color'];
    $timeout_date = $_POST['timeout_date'];

    $sql = "INSERT INTO task (user_id, title, note, color, timeout_date) 
            VALUES ('$users_id', '$title', '$note', '$color', '$timeout_date')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: /Code_Aj_Lak/home.php");
        exit(); 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>


