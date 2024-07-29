<?php
   include('../Components/toast.php');
   include('../Database/database.php');
   session_start();

   if(isset($_SESSION["admin_id"])) {
      $admin_id = $_SESSION["admin_id"];
   }else if(isset($_COOKIE['remember_admin'])) {
      $admin_id = $_COOKIE['remember_admin'];
   }else {
      $admin_id = '';
      header('location: admin_login.php');
   } 

   if(isset($_POST['submit'])){
      $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
      $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_SPECIAL_CHARS);

      try{
      $select_admin = $conn->prepare("SELECT * FROM admin WHERE name = ?");
      $select_admin->execute([$name]);
      
      if($select_admin->rowCount() > 0){
         echo '<script>show_toast("this username already exists" ,"error");</script>';
      }else{
         if($password != $confirm_password){
            echo '<script>show_toast("confirm password isn\'t matching" ,"error");</script>';
         }else{
            $hashed_password = password_hash($confirm_password, PASSWORD_DEFAULT);
            $insert_admin = $conn->prepare("INSERT INTO admin(name, password) VALUES(?,?)");
            $insert_admin->execute([$name, $hashed_password]);
            echo '<script>show_toast("new admin registered" ,"success");</script>';
         }
      }
      }catch (PDOException $e) {
         echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
      }
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

   <?php include '../components/admin_header.php' ?>
   <section class="form-container">

      <form action="" method="POST">
         <h3>register admin</h3>
         <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="password" maxlength="20" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="confirm_password" maxlength="20" required placeholder="Confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="register now" name="submit" class="btn">
      </form>

   </section>
   <script src="../JS/admin_script.js"></script>

</body>
</html>