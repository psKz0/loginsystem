<?php


session_start();
require_once 'db.php';

if  (isset($_POST['signup'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    $urole = 'user';
   
    if(empty($firstname)) {
        $_SESSION['error']='กรุณากรอกชื่อ';
        header("location: test.php");
    } else if (empty($lastname)) {
        $_SESSION['error']='กรุณากรอกนามสกุล';
        header("location: test.php");
    } else if (empty($email)) {
        $_SESSION['error']='กรุณากรอกอีเมล';
        header("location: test.php");

    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error']='รูปแบบอีเมลไม่ถูกต้อง';
        header("location: test.php");
        
    } else if (empty($password)) {
        $_SESSION['error']='กรุณากรอกรหัสผ่าน';
        header("location: test.php");
        
    } else if (strlen($_POST['password']) >20 || strlen($_POST['password']) <5) {
        $_SESSION['error']='รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
        header("location: test.php");
        
    } else if (empty($c_password)) {
        $_SESSION['error']='กรุณายืนยันรหัสผ่าน';
        header("location: test.php");
        
    } else if ($password != $c_password) {
        $_SESSION['error']='รหัสผ่านไม่ตรงกัน';
        header("location: test.php");
    
        
    } else {
        try {
            /* ทำเพื่อเช็คว่าอีเมลมีซ้ำกับในระบบไหม */
            $check_email = $conn->prepare("SELECT email FROM users WHERE email = :email");
            $check_email->bindParam(":email",$email);
            $check_email->execute();
            $row=$check_email->fetch(PDO::FETCH_ASSOC);

            if ($row['email'] == $email) {
                $_SESSION['warning']="มีอีเมล์นี้ในระบบแล้ว <a href='signin.php'>คลิกที่นี่</a>เพื่อเข้าสู่ระบบ";
                header("location: test.php");    
            } else if (!isset($_SESSION['error'])) {
                $passwordHash = password_hash($password,PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users(firstname, lastname , email, password ,urole) 
                                        VALUES(:firstname, :lastname, :email, :password, :urole)");
                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":password", $passwordHash);
                $stmt->bindParam(":urole", $urole);
                $stmt->execute();
                $_SESSION['success'] = "สมัครสมาชิกเสร็จสิ้น <a href='signin.php' class='alert-link'>คลิกที่นี้</a>เพื่อเข้าสู่ระบบ";
                header("location: test.php");
            } else  {
                $_SESSION['error'] = "มีบางอย่างผิดพลาด";
                header("location: test.php");
            }
        } catch(PDOException $e) {
        echo $e->getMessage();
    }
}  
}





?>