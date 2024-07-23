<?php

   include('../db/database.php');

   session_start();

   if(isset($_SESSION["staff_id"])) {
      $staff_id = $_SESSION["staff_id"];
   }else {
      $staff_id = '';
      header('location: staff_login.php');
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

<?php include '../components/staff_header.php' ?>

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
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM orders WHERE payment_status = ?");
            $select_pendings->execute([0]);
            while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
               $total_pendings += $fetch_pendings['total'];
            }
            $total_pendings = number_format($total_pendings);
         ?>
         <h3><span>Rs </span><?= $total_pendings; ?></h3>
         <p>Total pendings</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM orders WHERE payment_status = ?");
            $select_completes->execute([1]);
            while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
               $total_completes += $fetch_completes['total'];
            }
            $total_completes = number_format($total_completes);
         ?>
         <h3><span>Rs </span><?= $total_completes; ?></h3>
         <p>Total completes</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM orders");
            $select_orders->execute();
            $numbers_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $numbers_of_orders; ?></h3>
         <p>Total orders</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM products");
            $select_products->execute();
            $numbers_of_products = $select_products->rowCount();
         ?>
         <h3><?= $numbers_of_products; ?></h3>
         <p>Products added</p>
         <a href="products.php" class="btn">see products</a>
      </div>

      <div class="box">
         <?php
            $select_reservations = $conn->prepare("SELECT * FROM reservation WHERE status = ?");
            $select_reservations->execute(['pending']);
            $numbers_of_reservations = $select_reservations->rowCount();
         ?>
         <h3><?= $numbers_of_reservations; ?></h3>
         <p>Reservations pending</p>
         <a href="reservations.php" class="btn">see reservations</a>
      </div>

   </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>