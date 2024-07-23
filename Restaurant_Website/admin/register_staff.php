<?php

   include('../db/database.php');

   session_start();

   if(isset($_SESSION["admin_id"])) {
      $admin_id = $_SESSION["admin_id"];
   }else {
      $admin_id = '';
      header('location: admin_login.php');
   } 

   if(isset($_POST['submit'])){

      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $password = $_POST['password'];
      $password = filter_var($password, FILTER_SANITIZE_STRING);
      $confirm_password = $_POST['confirm_password'];
      $confirm_password = filter_var($confirm_password, FILTER_SANITIZE_STRING);

      $select_staff = $conn->prepare("SELECT * FROM staff WHERE name = ?");
      $select_staff->execute([$name]);
      
      if($select_staff->rowCount() > 0){
         $message[] = 'Username already exists!';
      }else{
         if($password != $confirm_password){
            $message[] = 'Confirm password is not matching!';
         }else{
            $hashed_password = password_hash($confirm_password, PASSWORD_DEFAULT);
            $insert_staff = $conn->prepare("INSERT INTO staff(name, password) VALUES(?,?)");
            $insert_staff->execute([$name, $hashed_password]);
            $message[] = 'New staff registered!';
         }
      }

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Staff register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

   <?php include '../components/admin_header.php' ?>
   <section class="form-container">

      <form action="" method="POST">
         <h3>register staff</h3>
         <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="password" maxlength="20" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="confirm_password" maxlength="20" required placeholder="Confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="register now" name="submit" class="btn">
      </form>

   </section>
   <script src="../js/admin_script.js"></script>

</body>
</html>