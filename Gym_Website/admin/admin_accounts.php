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

   if(isset($_GET['delete'])){
      try{
      $select_account = $conn->prepare("SELECT * FROM admin");
      $select_account->execute();
      if($select_account->rowCount() == 1) {
         echo '<script>show_toast("unable to delete with only one admin account" ,"error");</script>';
      }else {
         $delete_id = $_GET['delete'];
         $delete_admin = $conn->prepare("DELETE FROM admin WHERE id = ?");
         $delete_admin->execute([$delete_id]);
         if($admin_id == $delete_id) {
            $admin_id = '';
            session_unset();
            session_destroy();
            setcookie('remember_admin', $fetch_admin['admin_id'], time() + -1, "/");
            header('location: admin_login.php');
         }else {
            echo '<script>show_toast("admin removed" ,"success");</script>';
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
   <title>Admin accounts</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

   <?php include '../components/admin_header.php' ?>
   <section class="accounts">

      <h1 class="heading">admin accounts</h1>

      <div class="box-container">

         <div class="box">
            <p>Register new admin</p>
            <a href="register_admin.php" class="option-btn">register</a>
         </div>

         <?php
         try{
            $select_account = $conn->prepare("SELECT * FROM admin");
            $select_account->execute();
            if($select_account->rowCount() > 0){
               while($fetch_accounts = $select_account->fetch()){  
         ?>
         <div class="box">
            <p> Admin id : <span><?= $fetch_accounts['id']; ?></span> </p>
            <p> Username : <span><?= $fetch_accounts['name']; ?></span> </p>
            <div class="flex-btn">
               <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('delete this account?');">delete</a>
               <?php
                  if($fetch_accounts['id'] == $admin_id){
                     echo '<a href="update_profile.php" class="option-btn">update</a>';
                  }
               ?>
            </div>
         </div>
         <?php
            }
         }
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>
</section>

<script src="../JS/admin_script.js"></script>

</body>
</html>