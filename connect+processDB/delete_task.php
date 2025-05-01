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

if (isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    $deleted_at = date("Y-m-d H:i:s"); // เวลาที่กดลบ

    // เปลี่ยนสถานะเป็น 'deleted' และอัปเดตเวลาลบใน `deleted_at`
    $sql = "UPDATE task SET status = 'deleted', status_at = ? WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $deleted_at, $task_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Error: No task_id received";
}

$conn->close();
?>

