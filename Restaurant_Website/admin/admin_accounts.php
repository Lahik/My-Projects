<?php

   include('../db/database.php');

   session_start();

   if(isset($_SESSION["admin_id"])) {
      $admin_id = $_SESSION["admin_id"];
   }else {
      $admin_id = '';
      header('location: admin_login.php');
   }

   if(isset($_GET['delete'])){

      $select_account = $conn->prepare("SELECT * FROM admin");
      $select_account->execute();
      if($select_account->rowCount() == 1) {
         $message[] = 'Unable to delete when there is only one admin account';
      }else {
         $delete_id = $_GET['delete'];
         $delete_admin = $conn->prepare("DELETE FROM admin WHERE id = ?");
         $delete_admin->execute([$delete_id]);
         header('location: admin_accounts.php');
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
            $select_account = $conn->prepare("SELECT * FROM admin");
            $select_account->execute();
            if($select_account->rowCount() > 0){
               while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
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
         ?>

      </div>

</section>




















<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>