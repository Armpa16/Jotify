<?php
session_start(); // เริ่มต้นเซสชั่น

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

// ตรวจสอบว่า user_id มีค่าหรือไม่ใน session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // คำสั่ง SQL ดึงเฉพาะโน้ตที่ถูกลบ (status = 'deleted')
    $sql = "SELECT task.*, users.name, users.email 
            FROM task 
            JOIN users ON task.user_id = users.user_id
            WHERE task.user_id = '$user_id' AND task.status = 'deleted' 
            ORDER BY task.status_at DESC";

    $result = $conn->query($sql);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc(); // ดึงข้อมูลผู้ใช้
        $user_name = $row['name'];
        $user_email = $row['email'];
    } else {
        $user_name = "No name found";
        $user_email = "No email found";
    }
} else {
    echo "Error: User not logged in.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jotify - Trash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/trash.css">
    <!-- SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><!-- เรียกใช้ SweetAlert2 -->

</head>
<body>
    <!-- เเถบบาร์ด้านบน -->
    <header>
        <div class="logo">
            <img src="img/Jotify.png" alt="">
        </div>
        <div class="noti-user">
            <div class="noti">
                <i class="fa-solid fa-bell"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <div class="user_pro" id="userBtn">
                <i class="fa-solid fa-circle-user"></i></i>
            </div>
        </div>
    </header>

    <!-- ป๊อปอัพโปรไฟล์ -->
    <div class="profile-overlay" id="profileOverlay">
        <div id="profilePopup" class="popup" style="display: none; position: absolute; top: 90px; right: 45px; background: #EFF0F2; padding: 10px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
            <div class="head">
                <div class="usename">
                    <i class="fa-solid fa-circle-user"></i>&nbsp;<p><?php echo $user_name; ?></p>
                </div>
                <i class="fa-solid fa-xmark" id="closeProfilePopup" style="cursor: pointer;" ></i>
            </div>
            <?php echo $user_email; ?><br><br>
            <form method="POST" action="loginform.php">
                <button><i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;Log out</button>
            </form>
        </div>
        <!-- profilePopup -->
    </div>
    <!-- profile-overlay -->
    
    <div class="container">
        <!-- เเถบบาร์ด้านข้าง -->
        <div class="bar flex-column p-4" style="width: 280px; height: 1024px;">
            <div class="text-center py-4">
                <!-- <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none"> -->
                <button class="add-task-btn" id="addTaskBtn">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <!-- </a> -->
            </div>
            <hr>
            <ul class="nav nav-pills flex-column px-0">
                <li class="nav-item mb-3">
                    <a href="home.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-solid fa-house"></i>&nbsp;&nbsp;&nbsp;&nbsp;Home
                    </a>
                </li>
                <li class="mb-3">
                    <a href="neardeath.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-solid fa-circle-exclamation"></i>&nbsp;&nbsp;&nbsp;&nbsp;Near Death
                    </a>
                </li>
                <li class="mb-3">
                    <a href="calendar.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-solid fa-calendar-days"></i>&nbsp;&nbsp;&nbsp;&nbsp;Calendar
                    </a>
                </li>
                <li class="mb-3">
                    <a href="trash.php" class="nav-link active custom-nav">
                        <i class="fa-solid fa-trash"></i>&nbsp;&nbsp;&nbsp;&nbsp;Trash
                    </a>
                </li>
                <li class="mb-3">
                    <a href="done.php" class="nav-link link-body-emphasis custom-nav">
                        <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Done
                    </a>
                </li>
        </ul>
        </div>

        <div class="content">
            <!-- เเสดงโน๊ต -->
            <div class="dashboard_note" id="dashboard">
                <h1>Deleted Notes</h1><br>
                <div class="note-container">
                    <?php
                    // เชื่อมต่อฐานข้อมูล
                    $servername = "localhost";
                    $username = "root"; 
                    $password = ""; 
                    $dbname = "todolist";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // คิวรี่ดึงโน๊ตที่มีสถานะเป็น "delete"
                    $sql = "SELECT * FROM task WHERE user_id = ? AND status = 'deleted' ORDER BY status_at DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $_SESSION['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $count = 0;
                        while ($row = $result->fetch_assoc()) {
                            $count++;
                    ?>
                            <div class="note-item">
                                <div class="note-title">
                                    <h3 style="font-size: 30px; color: #333333;"><?php echo htmlspecialchars($row['title']); ?></h3> 
                                    <div class="task_status" style="background-color: <?php echo htmlspecialchars($row['color']); ?>"></div>
                                </div>
                                <hr style="width: 266px; margin: 20px auto; border: 1px solid #000000;">
                                <div class="note-content">
                                    <p><?php echo nl2br(htmlspecialchars($row['note'])); ?></p>
                                </div>
                                <div class="note-footer">
                                    <div class="foot-left">
                                        <i class="fa-solid fa-clock-rotate-left"></i>&nbsp;&nbsp;&nbsp;
                                        <span>
                                            <?php
                                                // คำนวณวันที่เหลือจากวันที่ที่ถูกลบ
                                                $deleted_at = strtotime($row['status_at']);
                                                $current_time = time(); // เวลา ณ ปัจจุบัน
                                                $time_left = 30 * 24 * 60 * 60 - ($current_time - $deleted_at); // คำนวณเวลาที่เหลือ (30 วัน)
                                                
                                                if ($time_left > 0) {
                                                    $days_left = floor($time_left / (60 * 60 * 24)); // จำนวนวันที่เหลือ
                                                    echo "กู้คืนได้ภายใน " . $days_left . " วัน";
                                                } else {
                                                    echo "หมดเวลาสำหรับการกู้คืนแล้ว";
                                                }
                                            ?>
                                        </span>
                                    </div>
                                    <!-- foot-left -->
                                    <button class="recover" data-task-id="<?php echo $row['task_id']; ?>">
                                        <i class="fa-solid fa-rotate"></i>
                                    </button>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p>No deleted notes</p>";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
                </div>
            </div>



        <!-- ส่วนกดเพิ่มโน๊ต -->
        <div class="modal-overlay" id="modalOverlay">
                <div class="modal-content modal-task"><br>
                    <form action="connect+processDB/add_note.php" method="POST">
                        <h1>Title</h1>
                        <div class="nametask">
                            <input type="text" placeholder="ป้อนชื่อรายการ..." name="title" required>&nbsp;&nbsp;&nbsp;
                            <div class="color-picker">
                                <button type="button" class="color-option" style="background: #238300;" data-color="#238300"></button>
                                <button type="button" class="color-option" style="background: #CC0000;" data-color="#CC0000"></button>
                                <button type="button" class="color-option" style="background: #D1A209;" data-color="#D1A209"></button>
                                <button type="button" class="color-option" ></button>
                            </div>
                            <input type="hidden" name="color" id="selectedColor">
                        </div>
                        <h2>Task a Note</h2>
                        <div class="note-container">
                            <textarea name="takenote" placeholder="Text..."></textarea>
                            <label for="imageUpload" class="upload-icon">
                                <i class="fa-solid fa-file-image"></i>
                            </label>
                            <input type="file" id="imageUpload" accept="image/*" style="display: none;"  name="note">
                            <!-- แสดงรูปที่อัปโหลด -->
                            <!-- <div id="imagePreviewContainer"></div> -->
                        </div>
                        <h3>Time out date</h3>
                        <div class="but-add">
                            <input type="date" name="timeout_date">
                            <div class="group_button">
                                <button class="add" id="add">Add +</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                <button class="cancle" id="cancle">Cancle</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- modal-content -->
            </div>
            <!-- modal-overlay -->
    </div>
    <!-- container -->



    <script>

        //========================================================================================================================//
        // เปิดป๊อปอัพโปรไฟล์
        const profileOverlay = document.getElementById("profileOverlay");
        document.getElementById('userBtn').addEventListener('click', function () {
            const popup = document.getElementById('profilePopup');
            // ตรวจสอบว่าป๊อปอัพแสดงอยู่หรือไม่
            if (popup.style.display === 'none' || popup.style.display === '') {
                popup.style.display = 'block'; // แสดงป๊อปอัพ
                profileOverlay.style.display = 'block'; // แสดง overlay
            } else {
                popup.style.display = 'none'; // ซ่อนป๊อปอัพ
                profileOverlay.style.display = 'none'; // ซ่อน overlay
            }
        });

        // ปิดป๊อปอัพโปรไฟล์เมื่อกดปุ่ม close
        document.getElementById('closeProfilePopup').addEventListener('click', function() {
            document.getElementById('profilePopup').style.display = 'none'; // ซ่อนป๊อปอัพ
            profileOverlay.style.display = 'none'; // ซ่อน overlay
        });

        // ปิดป๊อปอัพโปรไฟล์เมื่อคลิกภายนอก
        document.addEventListener('click', function (event) {
            const popup = document.getElementById('profilePopup');
            const userBtn = document.getElementById('userBtn');
            if (!userBtn.contains(event.target) && !popup.contains(event.target)) {
                popup.style.display = 'none'; // ซ่อนป๊อปอัพ
                profileOverlay.style.display = 'none'; // ซ่อน overlay
            }
        });

        //========================================================================================================================//
        const addTaskBtn = document.getElementById("addTaskBtn");
        const modalOverlay = document.getElementById("modalOverlay");
        const closeModal = document.getElementById("cancle");

        // เปิด popup add note
        addTaskBtn.addEventListener("click", function() {
            this.classList.toggle("active");
            modalOverlay.style.display = modalOverlay.style.display === "flex" ? "none" : "flex";
        });

        // ปิด popup add note
        closeModal.addEventListener("click", function() {
            modalOverlay.style.display = "none";
            addTaskBtn.classList.remove("active");
        });

        // ปิด popup add note เมื่อคลิกนอกกรอบ
        modalOverlay.addEventListener("click", function(event) {
            if (event.target === modalOverlay) {
                modalOverlay.style.display = "none";
                addTaskBtn.classList.remove("active");
            }
        });


        //========================================================================================================================//
        // ป้อปอัพกู้โน๊ต
        $(document).ready(function() {
            $(".recover").click(function() {
                var taskId = $(this).data("task-id"); // ดึง task_id จากปุ่มที่คลิก

                // ใช้ SweetAlert2 สำหรับป๊อปอัพยืนยัน
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่<br>ที่จะกู้คืนโน๊ตนี้?',
                    text: "การกู้คืนจะนำโน๊ตกลับไปยังหน้าหลัก",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#38b100',
                    cancelButtonColor: '#CC0000',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ส่งคำขอ AJAX เพื่อกู้คืนโน๊ต
                        $.ajax({
                            url: "connect+processDB/recover_task.php", // ปรับ URL ตามไฟล์ PHP ที่ใช้
                            type: "POST",
                            data: { task_id: taskId },
                            success: function(response) {
                                if (response.trim() === "success") {
                                    Swal.fire(
                                        'สำเร็จ!',
                                        'กู้คืนสำเร็จ!',
                                        'success'
                                    ).then(() => {
                                        location.reload(); // โหลดหน้าใหม่เพื่อแสดงการเปลี่ยนแปลง
                                    });
                                } else {
                                    Swal.fire(
                                        'เกิดข้อผิดพลาด!',
                                        'ไม่สามารถกู้คืนได้',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("AJAX Error: ", error);
                                Swal.fire(
                                    'เกิดข้อผิดพลาด!',
                                    'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });



        
    </script>
</body>
</html>