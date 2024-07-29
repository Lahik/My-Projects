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

   if(isset($_GET['delete'])) {
      try{
      $delete_email = $_GET['delete'];
      $delete_reservation = $conn->prepare("DELETE FROM newsletter WHERE email = ?");
      $delete_reservation->execute([$delete_email]);
      echo '<script>show_toast("newsletter deleted" ,"success");</script>';
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
   <title>Newsletters</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="placed-orders">

      <h1 class="heading">Newsletters</h1>

      <div class="box-container">
      <?php
      try{
         $select_newsletter = $conn->prepare("SELECT * FROM newsletter");
         $select_newsletter->execute();
         if($select_newsletter->rowCount() > 0){
         while($fetch_newsletter = $select_newsletter->fetch()){
      ?>
      <div class="box">
         <p> Name : <span><?= $fetch_newsletter['name']; ?></span> </p>
         <p> Email : <span><?= $fetch_newsletter['email']; ?></span> </p>
         <form action="" method="POST">
            <div class="flex-btn">
               <a href="newsletters.php?delete=<?= $fetch_newsletter['email']; ?>" class="delete-btn" onclick="return confirm('delete this newsletter?');">delete</a>
            </div>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No subscriptions for newsletter has been placed yet!</p>';
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