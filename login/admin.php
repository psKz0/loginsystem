<?php 
    session_start();
    /* ถ้ามี conn ต้องมี require once */
    require_once 'db.php';
    if (!isset($_SESSION['admin_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: signin.php');

    }



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <?php 

            if(isset($_SESSION['admin_login'])) {
                $admin_id = $_SESSION['admin_login'];
                $stmt = $conn->query("SELECT * FROM users WHERE id =$admin_id");
                $stmt->execute();
                /* fetch ข้อมูลมา ข้อมูลอยู่ในตัวแปร row */
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
            }


        ?>
        <h3 class="mt-4">Welcome Admin, <?php echo $row['firstname'] . ' ' .$row['lastname']; ?>  </h3>
            <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>