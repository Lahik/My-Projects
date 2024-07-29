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

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../CSS/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>welcome!</h3>
         <p style="text-transform: capitalize;"><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
      </div>

      <div class="box">
         <?php
         try{
            $select_admins = $conn->prepare("SELECT * FROM admin");
            $select_admins->execute();
            $numbers_of_admins = $select_admins->rowCount();
         ?>
         <h3><?= $numbers_of_admins; ?></h3>
         <p>Admins</p>
         <a href="admin_accounts.php" class="btn">see admins</a>
         <?php 
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>
      
      <div class="box">
         <?php
         try{
            $select_users = $conn->prepare("SELECT * FROM users");
            $select_users->execute();
            $numbers_of_users = $select_users->rowCount();
         ?>
         <h3><?= $numbers_of_users; ?></h3>
         <p>Users</p>
         <a href="user_accounts.php" class="btn">see users</a>
         <?php  
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>

      <div class="box">
         <?php
         try{
            $select_products = $conn->prepare("SELECT * FROM products");
            $select_products->execute();
            $numbers_of_products = $select_products->rowCount();
         ?>
         <h3><?= $numbers_of_products; ?></h3>
         <p>Products added</p>
         <a href="products.php" class="btn">see products</a>
         <?php  
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>
      
      <div class="box">
         <?php
         try{
            $select_members = $conn->prepare("SELECT * FROM membership GROUP BY user_id;");
            $select_members->execute();
            $total_members = $select_members->rowCount();
         ?>
         <h3><?= $total_members; ?></h3>
         <p>Total Members</p>
         <a href="memberships.php" class="btn">see members</a>
         <?php  
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>
      
      <div class="box">
         <?php
         try{
            $select_members = $conn->prepare("SELECT * FROM membership WHERE end > NOW() GROUP BY user_id;");
            $select_members->execute();
            $number_of_active_members = $select_members->rowCount();
         ?>
         <h3><?= $number_of_active_members; ?></h3>
         <p>Active Members</p>
         <a href="memberships.php" class="btn">see members</a>
         <?php  
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>

      <div class="box">
         <?php
         try{
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM orders WHERE payment_status = ?");
            $select_pendings->execute([0]);
            while($fetch_pendings = $select_pendings->fetch()){
               $total_pendings += $fetch_pendings['total'];
            }
            $total_pendings = number_format($total_pendings);
         ?>
         <h3><span>Rs </span><?= $total_pendings; ?></h3>
         <p>Total pendings</p>
         <a href="placed_orders.php" class="btn">see orders</a>
         <?php  
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>

      <div class="box">
         <?php
         try{
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM orders WHERE payment_status = ?");
            $select_completes->execute([1]);
            while($fetch_completes = $select_completes->fetch()){
               $total_completes += $fetch_completes['total'];
            }
            $total_completes = number_format($total_completes);
         ?>
         <h3><span>Rs </span><?= $total_completes; ?></h3>
         <p>Total completes</p>
         <a href="placed_orders.php" class="btn">see orders</a>
         <?php  
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>

      <div class="box">
         <?php
         try{
            $select_orders = $conn->prepare("SELECT * FROM orders");
            $select_orders->execute();
            $numbers_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $numbers_of_orders; ?></h3>
         <p>Total orders</p>
         <a href="placed_orders.php" class="btn">see orders</a>
         <?php  
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>

      <div class="box">
         <?php
         try{
            $select_messages = $conn->prepare("SELECT * FROM messages");
            $select_messages->execute();
            $numbers_of_messages = $select_messages->rowCount();
         ?>
         <h3><?= $numbers_of_messages; ?></h3>
         <p>New messages</p>
         <a href="messages.php" class="btn">see messages</a>
         <?php 
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>

      <div class="box">
         <?php
         try{
            $select_newsletter = $conn->prepare("SELECT * FROM newsletter");
            $select_newsletter->execute();
            $numbers_of_newsletter = $select_newsletter->rowCount();
         ?>
         <h3><?= $numbers_of_newsletter; ?></h3>
         <p>Newsletters</p>
         <a href="newsletters.php" class="btn">see newsletters</a>
         <?php 
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>

   </div>

</section>

<script src="../JS/admin_script.js"></script>

</body>
</html>