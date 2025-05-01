<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script>
              document.write(message);
        </script>   
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="css/loginform.css">
</head>
<body>
<?php
require_once 'vendor/autoload.php';

// init configuration
$clientID = '651967884826-56u1ak727unaisfqe2hi4s0pbv4e7up5.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/Code_Aj_Lak/loginform.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;
  $pic =  $google_account_info->picture;
  //ตรวจสอบว่าพบ
    echo "---------->".$name."==".$email;

    echo "<div align=\"center\"><img src=\"$pic\" width=\"25%\" class=\"w3-circle\"></div>";
     session_start(); 
     $_SESSION['email']=$email;
     $_SESSION['name']=$name;
     $_SESSION['user_id']=99;
     $_SESSION['user_pic'] = $pic;  // Ensure the picture is stored
     header("Location: home.php");
  // now you can use this profile info to create account in your website and make user logged in.
} else {
  //ตรวจสอบแล้วไม่พบ ไล่ไป login google

?>

    <div class="box_left">
        <img src="img/logo.png" alt="">
    </div>
    <!-- box_left -->

    <div class="box_right">
        <form method="POST" action="connect+processDB/login.php" > 
            <h1>Login</h1> 
            <br><br>              
            <div><input type="text" name="email" placeholder="Email" required></div><br>
            <div><input type="password" name="password"  placeholder="Password" required></div><br>
            <button type="submit">Login</button>  
            <a href="register.php">ลงทะเบียนผู้ใช้ใหม่</a><br>
            <hr width="585px" style="border: 1px solid black;"/><br>
            <a href="<?=$client->createAuthUrl()?>"><img src="img/google_login.png" alt="Login with Google"></a>
            <?php
} //อย่าลืมปิดตรงนี้
                if (isset($_GET['status'])) {
                    $status = $_GET['status'];
                    if ($status == 1 or $status == 2){
                        echo "<script>alert('ชื่อผู้ใช้งาน หรือ รหัสผ่าน ไม่ถูกต้อง');</script>";
                }
            }
            ?>

        </form>  
        
            
    </div>
    <!-- box_right -->
    </body>
</html>
