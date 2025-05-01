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


// ตรวจสอบว่ามีการส่งเดือนและปีมาหรือไม่
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// คำนวณค่าต่าง ๆ ของเดือน
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDayOfMonth);
$monthName = date('F', $firstDayOfMonth);
$dayOfWeek = date('N', $firstDayOfMonth);

// หาค่าของเดือนและปีถัดไป/ก่อนหน้า
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth == 0) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth == 13) {
    $nextMonth = 1;
    $nextYear++;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jotify - Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/calendar.css">
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
                    <a href="calendar.php" id="calendarMenu" class="nav-link active custom-nav">
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

        


        <!-- ส่วนของปฏิทินที่ซ่อนอยู่ -->
        <div class="calendar-container" id="calendarPanel">
            <div class="calendar-header">
                <span>Calendar</span>
                <!-- <button id="closeCalendar" class="close-btn">✖</button> -->
            </div>
            <div class="calendar-button">
                <a href="?month=<?= $prevMonth; ?>&year=<?= $prevYear; ?>" class="nav"><i class="fa-solid fa-chevron-left"></i></a>
                <span><?= $monthName . " " . $year; ?></span>
                <a href="?month=<?= $nextMonth; ?>&year=<?= $nextYear; ?>" class="nav"><i class="fa-solid fa-chevron-right"></i></a>
            </div>

            <!-- * สีจุดๆบนปฏิทิน * -->
            <?php
                // สร้างอาร์เรย์เก็บสีของโน๊ตที่อยู่ในแต่ละวัน
                $tasksByDate = [];

                $sql = "SELECT timeout_date, color FROM task 
                        WHERE user_id = '$user_id' 
                        AND MONTH(timeout_date) = '$month' 
                        AND YEAR(timeout_date) = '$year' 
                        AND status IS NULL";
                        
                $result = $conn->query($sql);

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $date = date('j', strtotime($row['timeout_date'])); // ดึงเฉพาะวันที่ (1-31)
                        $tasksByDate[$date][] = $row['color']; // เก็บสีของโน๊ตในแต่ละวัน
                    }
                }

                // เก็บวันที่ปัจจุบันไว้ใช้
                $currentDay = date('j');
                $currentMonth = date('m');
                $currentYear = date('Y');
                $selectedDay = isset($_GET['day']) ? $_GET['day'] : $currentDay;
                $selectedDate = $year . '-' . sprintf("%02d", $month) . '-' . sprintf("%02d", $selectedDay);
            ?>
            
            <table class="table">
                <tr>
                    <th class="table-head">Mon</th>
                    <th class="table-head">Tue</th>
                    <th class="table-head">Wed</th>
                    <th class="table-head">Thu</th>
                    <th class="table-head">Fri</th>
                    <th class="table-head">Sat</th>
                    <th class="table-head">Sun</th>
                </tr>
                <tr>
                <?php
                    $dayCount = 1;

                    // เว้นช่องว่างให้ตรงกับวันเริ่มต้นของเดือน
                    for ($i = 1; $i < $dayOfWeek; $i++) {
                        echo "<td></td>";
                    }
                
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        // ตรวจสอบว่าเป็นวันปัจจุบันหรือไม่
                        $todayClass = ($day == $currentDay && $month == $currentMonth && $year == $currentYear) ? 'today' : '';
                        // ตรวจสอบว่าเป็นวันที่เลือกหรือไม่
                        $selectedClass = ($day == $selectedDay) ? 'selected-date' : '';
                        
                        // ตรวจสอบว่ามี Task ในวันนั้นหรือไม่
                        $dots = "";
                        if (isset($tasksByDate[$day])) {
                            foreach ($tasksByDate[$day] as $color) {
                                $dots .= "<span class='task-dot' style='background-color: $color;'></span>";
                            }
                        }
                    
                        // ตรวจสอบว่าเซลล์นั้นเป็น active หรือไม่
                        $activeClass = ($day == $selectedDay) ? 'active-date' : '';
                    
                        // สร้างวันที่ในตาราง
                        echo "<td class='$todayClass $activeClass Date-number clickable-date' data-day='$day'> 
                                <a href='?month=$month&year=$year&day=$day' class='date-link'>$day</a> 
                                <div class='dots-container'>$dots</div> 
                              </td>";
    
                        if (($dayCount + $dayOfWeek - 1) % 7 == 0) {
                            echo "</tr><tr>";
                        }
                        $dayCount++;
                    }
                ?>
                </tr>
            </table>
            <div class="calendar-buttom">
                <span>Tasks for <?php echo date('F j, Y', strtotime($selectedDate)); ?>:</span>
                <!-- แสดงโน๊ตตามวันที่เลือก -->
                <div class="dashboard_note" id="dashboard">
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

                        // คิวรี่ดึงเฉพาะโน๊ตที่มี status เป็น 'null' และ timeout_date ตรงกับวันที่ที่เลือก
                        $sql = "SELECT * FROM task WHERE user_id = ? AND status IS NULL AND timeout_date = ? ORDER BY created_at DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("is", $_SESSION['user_id'], $selectedDate);
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
                                    <button class="edit" data-task-id="<?php echo $row['task_id']; ?>" data-title="<?php echo $row['title']; ?>" data-note="<?php echo $row['note']; ?>"><i class="fa-solid fa-pen-to-square"></i></button>
                                </div>
                            </div>
                        <?php
                            }
                        } else {
                            echo "<p>No notes for this date</p>";
                        }
                        ?>
                    </div>
                    <!-- note-container -->
                </div>
                <!-- dashboard_note -->
            </div>
            <!-- calendar-buttom -->
        </div>
        <!-- calendar-container -->
         
        
        <div class="content">
            <!-- เเสดงโน๊ต -->
            <div class="dashboard_note" id="dashboard">
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
    </div>
    <!-- content -->

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
        // โน๊ต
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
        // เเถบปฏิทิน
        document.addEventListener("DOMContentLoaded", function () {
            const calendarMenu = document.getElementById("calendarMenu");
            const calendarPanel = document.getElementById("calendarPanel");

            // เมื่อกดเมนู Calendar ให้เปิดแถบปฏิทิน
            calendarMenu.addEventListener("click", function (event) {
                event.preventDefault(); // ป้องกันการโหลดหน้าใหม่
                calendarPanel.classList.toggle("open"); // เปิด/ปิดแถบปฏิทิน
            });

            // ปิดแถบปฏิทินเมื่อคลิกนอกแถบปฏิทิน
            document.addEventListener("click", function (event) {
                // ตรวจสอบว่าไม่ใช่การคลิกที่แถบปฏิทินหรือเมนู
                if (!calendarPanel.contains(event.target) && !calendarMenu.contains(event.target)) {
                    calendarPanel.classList.remove("open"); // ซ่อนแถบปฏิทิน
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const calendarContainer = document.querySelector(".calendar-container");

            // ตรวจสอบว่าอยู่หน้า calendar.php หรือไม่
            if (window.location.pathname.includes("calendar.php")) { 
                calendarContainer.classList.add("open"); // เปิดปฏิทินอัตโนมัติ
            }

            // ตรวจจับการกดปุ่มเปลี่ยนเดือน
            document.querySelectorAll(".calendar-button .nav").forEach(button => {
                button.addEventListener("click", function () {
                    setTimeout(() => {
                        calendarContainer.classList.add("open"); // ให้ปฏิทินเปิดอยู่ตลอด
                    }, ); // หน่วงเวลาเล็กน้อยเพื่อให้การเปลี่ยนเดือนทำงานก่อน
                });
            });
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
        
     //========================================================================================================================//
        // คลิกวันที่ เเสดงโน๊ตตามวัน
        document.addEventListener('DOMContentLoaded', function() {
            // เพิ่ม event click ให้กับเซลล์วันที่
            const dateCells = document.querySelectorAll('.clickable-date');
            
            dateCells.forEach(cell => {
                cell.addEventListener('click', function() {
                    // ลบคลาส active-date จากทุกเซลล์
                    document.querySelectorAll('.clickable-date').forEach(el => {
                        el.classList.remove('active-date');
                    });

                    // ลบคลาส today จากทุกเซลล์ (ไม่ให้ today เป็น active)
                    document.querySelectorAll('.today').forEach(el => {
                        el.classList.remove('today');
                    });

                    // เพิ่มคลาส active-date ให้กับเซลล์ที่คลิก
                    this.classList.add('active-date');
                });
            });
        });


    </script>
</body>
</html>