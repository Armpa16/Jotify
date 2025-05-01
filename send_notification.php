<!--ไม่มีไร เเค่ไฟล์เทสส่งอีเมลเตือน -->

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; //  โหลด PHPMailer
session_start(); //เรียกใช้ session

//  เช็คว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    die(" กรุณาล็อกอินก่อนส่งอีเมลแจ้งเตือน");
}

//  รับข้อมูลผู้ใช้ที่ล็อกอินอยู่
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['email'];

//  ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "todolist";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // แสดงข้อผิดพลาดหากเชื่อมต่อไม่ได้
}

//  คิวรีหางานที่ใกล้ครบกำหนด
$sql = "SELECT task_id, title, timeout_date, note 
        FROM task 
        WHERE user_id = ? 
        AND timeout_date BETWEEN CURDATE() AND CURDATE() + INTERVAL 2 DAY 
        AND status IS NULL";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $mail = new PHPMailer(true);
    try {
        //  ตั้งค่าเซิร์ฟเวอร์ SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'panudech1419@gmail.com'; //  อีเมลผู้ส่ง
        $mail->Password   = 'ajtlmeokzhxznief'; //  App Password ไม่มีช่องว่าง
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet = "UTF-8"; //  รองรับภาษาไทย
        $mail->SMTPDebug = 2; //  เปิด Debug Mode (เอาออกถ้าใช้จริง)

        // ตั้งค่าผู้ส่งและผู้รับ
        $mail->setFrom('panudech1419@gmail.com', 'TodoList Reminder');
        $mail->addAddress($user_email); //  ส่งไปยังอีเมลผู้ใช้ที่ล็อกอิน

        //  สร้างเนื้อหาอีเมล
        $bodyContent = "<h3>แจ้งเตือนงานใกล้ครบกำหนด</h3>";
        while ($row = $result->fetch_assoc()) {
            $bodyContent .= "<p><strong>งาน:</strong> " . htmlspecialchars($row['title']) . "</p>";
            $bodyContent .= "<p><strong>กำหนดส่ง:</strong> " . htmlspecialchars($row['timeout_date']) . "</p>";
            $bodyContent .= "<p><strong>รายละเอียด:</strong> " . nl2br(htmlspecialchars($row['note'])) . "</p><hr>";
        }
        $bodyContent .= "<p style='color:red;'>กรุณาทำให้เสร็จก่อนกำหนด!</p>";

        //  ตั้งค่าหัวข้อและเนื้อหาอีเมล
        $mail->isHTML(true);
        $mail->Subject = "แจ้งเตือน Task ใกล้ครบกำหนด";
        $mail->Body    = $bodyContent;

        //  ส่งอีเมล
        if ($mail->send()) {
            echo " อีเมลแจ้งเตือนถูกส่งไปที่ " . $user_email . "<br>";
        } else {
            echo "เกิดข้อผิดพลาดในการส่งอีเมล: " . $mail->ErrorInfo; // แสดงข้อความเมื่อส่งอีเมลไม่สำเร็จ
        }
    } catch (Exception $e) {
        echo " เกิดข้อผิดพลาดในการส่งอีเมล: {$mail->ErrorInfo}"; // แสดงข้อผิดพลาดจาก PHPMailer
    }
} else {
    echo " ไม่มีงานที่ใกล้ครบกำหนด";
}

$stmt->close();
$conn->close();
?>


