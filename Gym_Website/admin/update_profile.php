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
      $current_password = filter_input(INPUT_POST, "current_password", FILTER_SANITIZE_SPECIAL_CHARS);
      $new_password = filter_input(INPUT_POST, "new_password", FILTER_SANITIZE_SPECIAL_CHARS);
      $confirm_password = filter_input(INPUT_POST, "confirm_password", FILTER_SANITIZE_SPECIAL_CHARS);

      try{
      $select_current_pass = $conn->prepare("SELECT password FROM admin WHERE id = ?");
      $select_current_pass->execute([$admin_id]);
      $fetch_current_pass = $select_current_pass->fetch();
      $db_password = $fetch_current_pass['password'];

      if(!(password_verify($current_password, $db_password))) {
         echo '<script>show_toast("current password isn\'t matching" ,"error");</script>';
      }else if($new_password != $confirm_password){
         echo '<script>show_toast("confirm password isn\'t matching" ,"error");</script>';
      }else{
         $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);

         $select_name = $conn->prepare("SELECT * FROM admin WHERE name = ? AND id != ?");
         $select_name->execute([$name, $admin_id]);

         if($select_name->rowCount() > 0) {
            echo '<script>show_toast("this username already registered" ,"invalid");</script>';
         }else {
            if ($name !== "") {
               $hashed_password = password_hash($confirm_password, PASSWORD_DEFAULT);
               $update_password = $conn->prepare("UPDATE admin SET password = ?, name = ? WHERE id = ?");
               $update_password->execute([$hashed_password, $name, $admin_id]);
               echo '<script>show_toast("profile updated" ,"success");</script>';
           } else {
               $hashed_password = password_hash($confirm_password, PASSWORD_DEFAULT);
               $update_password = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
               $update_password->execute([$hashed_password, $admin_id]);
               echo '<script>show_toast("password updated, name unchanged" ,"success");</script>';
           }
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
   <title>Update profile</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="form-container">

      <form action="" method="POST">
         <h3>update profile</h3>
         <input type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['name']; ?>">
         <input type="password" name="current_password" maxlength="20" placeholder="Enter your current password" class="box" oninput="this.value = this.value.replace(/\s/g, '')" required>
         <input type="password" name="new_password" maxlength="20" placeholder="Enter your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')" required>
         <input type="password" name="confirm_password" maxlength="20" placeholder="Confirm your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')" required>
         <input type="submit" value="update profile" name="submit" class="btn">
      </form>

   </section>

   <script src="../JS/admin_script.js"></script>

</body>
</html>