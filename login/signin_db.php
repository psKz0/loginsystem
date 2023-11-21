<?php


session_start();
require_once 'db.php';


if  (isset($_POST['signin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
   
   
   /* เช็คอีเมลถ้าไม่ใส่ให้ขึ้น เช็ครหัสว่ามีไหม */
    if (empty($email)) {
        $_SESSION['error']='กรุณากรอกอีเมล';
        header("location: signin.php");

    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error']='รูปแบบอีเมลไม่ถูกต้อง';
        header("location: signin.php");
        
    } else if (empty($password)) {
        $_SESSION['error']='กรุณากรอกรหัสผ่าน';
        header("location: signin.php");
        
    } else if (strlen($_POST['password']) >20 || strlen($_POST['password']) <5) {
        $_SESSION['error']='รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
        header("location: signin.php");
    } else {
        try {
            /* ทำเพื่อเช็คว่าอีเมลมีซ้ำกับในระบบไหม */
            $check_data = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $check_data->bindParam(":email",$email);
            $check_data->execute();
            /* fetch ข้อมูลมาเก็บไว้ในแปร $row */
            $row=$check_data->fetch(PDO::FETCH_ASSOC);

            /* เช็คว่ามีข้อมูลในระบบไหม ถ้ามากกว่า 0 คือมีข้อมูลในระบบ rowCount */
            if ($check_data->rowCount() > 0) {
                
                if ($email == $row['email']) {
                    /* เช็คว่ารหัสตรงกันไหมระหว่าง $password กับ $row['password'] */
                    if (password_verify($password, $row['password'])) {
                        if ($row['urole'] == 'admin') {
                            $_SESSION['admin_login'] = $row['id'];
                            header("location: admin.php");
                        } else {
                            $_SESSION['user_login'] = $row['id'];
                            header("location: user.php");
                        }
                    } else {
                        $_SESSION['error'] = 'รหัสผ่านผิด' ;
                        header("location: signin.php");
                    }

                } else {
                    $_SESSION['error'] = 'อีเมลผิด' ;
                        header("location: signin.php");
                }
  
            
            } else  {
                $_SESSION['error'] = "ไม่มีอีเมล์นี้ในระบบ โปรดตรวจสอบอีกครั้งนึง";
                header("location: signin.php");
            }
        } catch(PDOException $e) {
        echo $e->getMessage();
    }
}  
}





?>