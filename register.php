<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>       
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="box_left">
        <form method="POST" action="connect+processDB/insertUser.php" >    
            <h1>กรุณากรอกข้อมูลด้านล่าง</h1>
            <div><input type="text" name="name" placeholder="ชื่อ" required> &nbsp;&nbsp; <input type="text" name="surname" placeholder="นามสกุล" required></div><br>
            <div><input type="email" name="email" placeholder="อีเมล" required></div><br>
            <div><input type="password" name="password" placeholder="รหัสผ่าน" required></div><br>
            <div><input type="password" name="c_password" placeholder="ยืนยันรหัสผ่าน" required></div><br>
            <button type="submit">สร้างบัญชีผู้ใช้</button>  
            <a href="loginform.php">มีบัญชีผู้ใช้แล้ว? ลงชื่อเข้าใช้</a><br>     
        </form>  
        <br><br>
        <?php
            if(isset($_GET['status'])){
                //ตรวจสอบพบ error 
                echo "<script>";
                $status=$_GET['status'];
            if($status==1){
                echo "alert(\"ไม่พบผู้ใช้งาน\")";
            }else if($status==2) {
                //echo "alert(\"รหัสผ่านไม่ถูกต้อง\")";
                echo "alert(\"รหัสผ่านไม่ถูกต้อง\");";
            }
                echo "</script>";
            }
        ?>
    </div>
    <!-- box_left -->

    <div class="box_right">
        <h1>Welcome!</h1>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting <br>
            industry. Lorem Ipsum has been the industry's standard dummy <br>
            text ever since the 1500s, when an unknown printer took a galley</p>
    </div> 
    <!-- box_right -->
    
            
      
        
        
</body>
</html>
