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
      $delete_id = $_GET['delete'];
      $delete_users = $conn->prepare("DELETE FROM users WHERE id = ?");
      $delete_order = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
      $delete_cart = $conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
      $delete_reservation = $conn->prepare("DELETE FROM reservation WHERE user_id = ?");

      $delete_users->execute([$delete_id]);
      $delete_order->execute([$delete_id]);
      $delete_cart->execute([$delete_id]);
      $delete_reservation->execute([$delete_id]);
      
      header('location: user_accounts.php');
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User accounts</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="accounts">

      <h1 class="heading">user accounts</h1>

      <div class="box-container">

      <?php
         $select_account = $conn->prepare("SELECT * FROM users");
         $select_account->execute();
         if($select_account->rowCount() > 0){
            while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <div class="box">
         <p> ID : <span><?= $fetch_accounts['id']; ?></span> </p>
         <p> Name : <span><?= $fetch_accounts['name']; ?></span> </p>
         <a href="user_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No accounts available</p>';
      }
      ?>

      </div>

   </section>

   <script src="../js/admin_script.js"></script>

</body>
</html>