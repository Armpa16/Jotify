<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todolist";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบค่า task_id ว่ามีหรือไม่
if (!isset($_POST['task_id']) || !is_numeric($_POST['task_id'])) {
    die("Error: Invalid Task ID");
}

$task_id = intval($_POST['task_id']);
$title = $conn->real_escape_string($_POST['title']);
$takenote = $conn->real_escape_string($_POST['takenote']);
$color = $conn->real_escape_string($_POST['color']);
$timeout_date = $conn->real_escape_string($_POST['timeout_date']);

// ตรวจสอบว่า task_id มีอยู่ในฐานข้อมูลหรือไม่
$check_sql = "SELECT * FROM task WHERE task_id = $task_id";
$check_result = $conn->query($check_sql);
if ($check_result->num_rows == 0) {
    die("Error: Task ID does not exist!");
}

// อัปเดตข้อมูล
$sql = "UPDATE task SET title='$title', note='$takenote', color='$color', timeout_date='$timeout_date' WHERE task_id=$task_id";

if ($conn->query($sql) === TRUE) {
    header("Location: /Code_Aj_Lak/home.php");
    exit();
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>


