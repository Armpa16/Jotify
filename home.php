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

    // คำสั่ง SQL ดึงข้อมูลจากตาราง task และ users
    $sql = "SELECT task.*, users.name, users.email 
            FROM task 
            JOIN users ON task.user_id = users.user_id
            WHERE task.user_id = '$user_id' 
            ORDER BY task.created_at DESC";
    
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
    <title>Jotify - Notes Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/home.css">
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
                    <a href="home.php" class="nav-link active custom-nav">
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
                    <a href="trash.php" class="nav-link link-body-emphasis custom-nav">
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
        <!-- bar -->


        <div class="content">
            <!-- เเสดงโน๊ต -->
            <div class="dashboard_note" id="dashboard">
                <h1>Recent</h1><br>
                <div class="note-container">
                    <?php
                    // คิวรี่ดึงเฉพาะโน๊ตล่าสุดมา
                    $query_recent = "SELECT * FROM task WHERE (status IS NULL OR status = '') AND user_id = ? ORDER BY created_at DESC LIMIT 2"; // เลือกโน๊ตล่าสุด (เพิ่ม LIMIT 2)
                    $stmt_recent = $conn->prepare($query_recent);
                    $stmt_recent->bind_param("i", $user_id); // ถ้าใช้ session หรือการระบุผู้ใช้
                    $stmt_recent->execute();
                    $result_recent = $stmt_recent->get_result();

                    if ($result_recent->num_rows > 0) {
                        while ($row = $result_recent->fetch_assoc()) {
                            ?>
                            <div class="note-item">
                                <div class="note-title">
                                    <h3 style="font-size: 30px; color: #333333;"><?php echo $row['title']; ?></h3> 
                                    <div class="task_status" style="background-color: <?php echo $row['color']; ?>"></div>
                                </div>
                                <hr style="width: 266px; margin: 20px auto; border: 1px solid #000000;">
                                <div class="note-content">
                                    <p><?php echo $row['note']; ?></p>
                                </div>
                                <div class="note-footer">
                                    <button class="done" id="done" data-task-id="<?php echo $row['task_id']; ?>"><i class="fa-solid fa-check">&nbsp;&nbsp;Done</i></button>
                                    <button class="delete" data-task-id="<?php echo $row['task_id']; ?>"><i class="fa-regular fa-trash-can"></i></button>
                                    <button class="edit" data-task-id="<?php echo $row['task_id']; ?>" data-title="<?php echo $row['title']; ?>" data-note="<?php echo $row['note']; ?>"><i class="fa-solid fa-pen-to-square"></i>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No recent notes found</p>";
                    }
                    ?>
                </div>
                <br>
                <hr style="width: 1080px; margin: 20px auto; border: 1px solid #000000;">
                <h2>My Notes</h2><br>
                <div class="note-container">
                    <?php
                    // คิวรี่ดึงเฉพาะโน้ตที่ยังไม่ Done
                    $query = "SELECT * FROM task WHERE (status IS NULL OR status = '') AND user_id = ? ORDER BY created_at DESC";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id); 
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $count = 0;
                        while ($row = $result->fetch_assoc()) {
                            $count++;
                            ?>
                            <div class="note-item">
                                <div class="note-title">
                                    <h3 style="font-size: 30px; color: #333333;"><?php echo $row['title']; ?></h3> 
                                    <div class="task_status" style="background-color: <?php echo $row['color']; ?>"></div>
                                </div>
                                <hr style="width: 266px; margin: 20px auto; border: 1px solid #000000;">
                                <div class="note-content">
                                    <p><?php echo $row['note']; ?></p>
                                </div>
                                <div class="note-footer">
                                    <button class="done" id="done" data-task-id="<?php echo $row['task_id']; ?>"><i class="fa-solid fa-check">&nbsp;&nbsp;Done</i></button>
                                    <button class="delete" data-task-id="<?php echo $row['task_id']; ?>"><i class="fa-regular fa-trash-can"></i></button>
                                    <button class="edit" data-task-id="<?php echo $row['task_id']; ?>" data-title="<?php echo $row['title']; ?>" data-note="<?php echo $row['note']; ?>"><i class="fa-solid fa-pen-to-square"></i>
                                </div>
                            </div>
                            <?php
                            }
                        } else {
                                echo "<p>No notes found</p>";
                        }
                    ?>
                </div>
                <!-- note-container -->
            </div>
            <!-- dashboard_note -->


            <!-- ป๊อปอัพสำหรับการแก้ไข -->
            <div class="edit-overlay" id="editOverlay">
                <div id="editPopup" class="modal-content modal-task" style="display: none; position: fixed; top: 47%; padding-top: 45px; left: 50%; transform: translate(-50%, -50%); background: white;  border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); z-index: 9999;">
                    <form action="connect+processDB/update_note.php" method="POST">
                        <!-- ตรวจสอบว่ามี input task_id -->
                        <input type="hidden" name="task_id" id="task_id">
                        <h1>Title</h1>
                        <div class="nametask">
                            <!-- ฟิลด์ title จะถูกเติมข้อมูลด้วยค่าของเดิมที่ดึงมาจากฐานข้อมูล -->
                            <input type="text" placeholder="ป้อนชื่อรายการ..." name="title" id="editTitle" required>&nbsp;&nbsp;&nbsp;
                            <div class="color-picker">
                                <button type="button" class="color-option" style="background: #238300;" data-color="#238300"></button>
                                <button type="button" class="color-option" style="background: #CC0000;" data-color="#CC0000"></button>
                                <button type="button" class="color-option" style="background: #D1A209;" data-color="#D1A209"></button>
                                <button type="button" class="color-option"></button>
                            </div>
                            <input type="hidden" name="color" id="selectedColor">
                        </div>
                        <h2>Task a Note</h2>
                        <div class="note-container">
                            <!-- ฟิลด์ note จะถูกเติมข้อมูลด้วยค่าของเดิมที่ดึงมาจากฐานข้อมูล -->
                            <textarea name="takenote" id="editNote" placeholder="Text..."><?php echo isset($task['note']) ? $task['note'] : ''; ?></textarea>
                            <label for="imageUpload" class="upload-icon">
                                <i class="fa-solid fa-file-image"></i>
                            </label>
                            <input type="file" id="imageUpload" accept="image/*" style="display: none;" name="note">
                        </div>
                        <h3>Time out date</h3>
                        <div class="but-add">
                            <!-- ฟิลด์ timeout_date จะถูกเติมข้อมูลด้วยค่าของเดิมที่ดึงมาจากฐานข้อมูล -->
                            <input type="date" name="timeout_date" id="editTimeoutDate" value="<?php echo isset($task['timeout_date']) ? $task['timeout_date'] : ''; ?>">
                            <div class="group_button">
                                <button class="task_edit" id="task_id">Edit</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                <button class="cancle" type="button" id="closeEditPopup">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- editPopup -->
            </div>
            <!-- edit-overlay -->



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
        <!-- content -->
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
        // ปุ่มเเก้โน๊ต
        const editOverlay = document.getElementById("editOverlay");
        document.querySelectorAll('.edit').forEach(function(editButton) {
            editButton.addEventListener('click', function() {
                var taskId = this.getAttribute('data-task-id'); // ดึง task_id จากปุ่ม edit
                var title = this.getAttribute('data-title');
                var note = this.getAttribute('data-note');

                document.getElementById('editTitle').value = title;
                document.getElementById('editNote').value = note;
                document.getElementById('task_id').value = taskId; // ใส่ task_id ลงในฟอร์ม

                console.log("Task ID set:", taskId); // ตรวจสอบว่ามีค่า task_id

                document.getElementById('editPopup').style.display = 'block'; // แสดงป๊อปอัพ
                editOverlay.style.display = 'block'; // แสดง overlay
            });
        });

        // ปุ่ม Cancel เพื่อปิดป๊อปอัพและ overlay
        document.getElementById('closeEditPopup').addEventListener('click', function() {
            document.getElementById('editPopup').style.display = 'none'; // ซ่อนป๊อปอัพเมื่อกดปุ่ม Cancel
            editOverlay.style.display = 'none'; // ซ่อน overlay เมื่อกดปุ่ม Cancel
        });

        




        //========================================================================================================================//
        // note
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
        // / เลือกสี โน๊ต
        document.addEventListener("DOMContentLoaded", function () {
        const colorButtons = document.querySelectorAll(".color-option");
        const colorInput = document.getElementById("selectedColor");
        const customColor = document.getElementById("customColor");

        colorButtons.forEach(button => {
            button.addEventListener("click", function () {
                colorButtons.forEach(btn => btn.classList.remove("selected"));
                this.classList.add("selected");

                if (this === customColor) {
                    colorInput.value = customColor.value;
                } else {
                    colorInput.value = this.dataset.color;
                }
            });
        });
        customColor.addEventListener("input", function () {
            colorInput.value = this.value;
        });
    });


    //========================================================================================================================//
        
        // ส่งโน๊ตไปหน้า done
        $(document).ready(function() {
            $(".done").click(function() {
                var taskId = $(this).data("task-id"); // ดึงค่า task_id จากปุ่ม
                var noteItem = $(this).closest(".note-item"); // เลือกโน้ตที่ต้องซ่อน

                console.log("Task ID ที่ส่งไป: ", taskId); // ตรวจสอบค่า task_id ที่จะส่งไป

                $.ajax({
                    url: "connect+processDB/update_task_status.php",
                    type: "POST",
                    data: { task_id: taskId },
                    success: function(response) {
                        console.log("Response จาก PHP: ", response); // แสดงค่าที่ได้รับจาก PHP
                        if (response.trim() === "success") {
                            noteItem.fadeOut("slow", function() {
                                $(this).remove(); // ลบโน้ตออกจากหน้า
                            });
                        } else {
                            alert("เกิดข้อผิดพลาด กรุณาลองใหม่\n" + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", error);
                        alert("เกิดข้อผิดพลาดในการส่งคำขอ"); 
                    }
                });
            });
        });


 //========================================================================================================================//
        // ป้อปอัพลบโน๊ต
        $(document).ready(function() {
            $(".delete").click(function() {
                var taskId = $(this).data("task-id"); // ดึงค่า task_id จากปุ่ม
                var noteItem = $(this).closest(".note-item"); // ดึง โน๊ตที่ต้องการลบ

                Swal.fire({
                    title: "ยืนยันการลบ?",
                    text: "คุณแน่ใจหรือไม่ว่าต้องการลบโน้ตนี้?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#CC0000",
                    cancelButtonColor: "#333333",
                    confirmButtonText: "ใช่, ลบเลย!",
                    cancelButtonText: "ยกเลิก"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "connect+processDB/delete_task.php", 
                            type: "POST",
                            data: { task_id: taskId },
                            success: function(response) {
                                if (response.trim() === "success") {
                                    Swal.fire("ลบสำเร็จ!", "โน้ตถูกลบแล้ว", "success").then(() => {
                                        noteItem.fadeOut("slow", function() {
                                            $(this).remove(); // ลบออกจากหน้า
                                            location.reload(); // รีเฟรชหน้า
                                        });
                                    });
                                } else {
                                    Swal.fire("เกิดข้อผิดพลาด!", response, "error");
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire("เกิดข้อผิดพลาด!", "ไม่สามารถลบโน้ตได้", "error");
                            }
                        });
                    }
                });
            });
        });




        // document.getElementById("imageUpload").addEventListener("change", function(event) {
        // const file = event.target.files[0];
        // if (file) {
        //     const reader = new FileReader();
        //     reader.onload = function(e) {
        //         // แสดงรูปใน div
        //         const img = document.createElement("img");
        //         img.src = e.target.result;
        //         img.alt = "อัปโหลดรูป";
        //         img.classList.add("preview-img"); // เพิ่ม class ให้รูป

        //         // ล้างภาพเก่าก่อนแสดงใหม่
        //         const previewContainer = document.getElementById("imagePreviewContainer");
        //         previewContainer.innerHTML = "";
        //         previewContainer.appendChild(img);

        //         // แทรก URL รูปภาพลงใน textarea
        //         const textarea = document.getElementById("takenote");
        //         textarea.value += `\n[รูปภาพแนบ: ${file.name}]`;
        //         };
        //         reader.readAsDataURL(file);
        //     }
        // });
    </script>
</body>
</html>
