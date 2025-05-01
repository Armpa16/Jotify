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

    // อัปเดตสถานะของ task กลับเป็น NULL
    $sql = "UPDATE task SET status = NULL, status_at = NULL WHERE task_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $_SESSION['user_id']); // ตรวจสอบว่าเป็นของผู้ใช้ที่ล็อกอินอยู่

    if ($stmt->execute()) {
        echo "success"; // ส่งคำตอบกลับ
    } else {
        echo "Error: " . $conn->error; // ถ้ามีข้อผิดพลาด
    }

    $stmt->close();
} else {
    echo "Error: No task_id received";
}

$conn->close();
?>


