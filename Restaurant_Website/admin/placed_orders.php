<?php
   include('../db/database.php');

   session_start();

   if(isset($_SESSION["admin_id"])) {
      $admin_id = $_SESSION["admin_id"];
   }else {
      $admin_id = '';
      header('location: admin_login.php');
   } 

   if(isset($_POST['update_payment'])){
      $order_id = $_POST['order_id'];
      $_POST['payment_status'] == 'paid'? $payment_status = 1 : $payment_status = 0 ;
      $update_status = $conn->prepare("UPDATE orders SET payment_status = ? WHERE order_id = ?");
      $update_status->execute([$payment_status, $order_id]);
      $message[] = 'payment status updated!';
   }

   if(isset($_GET['delete'])){
      $delete_id = $_GET['delete'];
      $delete_order = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
      $delete_order->execute([$delete_id]);
      header('location: placed_orders.php');
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="placed-orders">

      <h1 class="heading">placed orders</h1>

      <div class="box-container">

      <?php
         $select_orders = $conn->prepare("SELECT * FROM orders");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <p> User id : <span><?= $fetch_orders['user_id']; ?></span> </p>
         <p> Placed on : <span><?= $fetch_orders['order_date_time']; ?></span> </p>
         <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
         <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
         <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
         <p> Total products : <span><?= $fetch_orders['orders']; ?></span> </p>
         <p> Total price : <span>Rs <?= $fetch_orders['total']; ?></span> </p>
         <p> Payment method : <span><?= $fetch_orders['payment_method']; ?></span> </p>
         <form action="" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['order_id']; ?>">
            <select name="payment_status" class="drop-down">
               <?php 
                  if($fetch_orders['payment_status'] == 1) {
                     $selected = 'paid';
                     $not_selected = 'pending';
                  }else {
                     $selected = 'pending';
                     $not_selected = 'paid';
                  }
               ?>
               <option value="<?= $selected ?>" selected><?= $selected ?></option>
               <option value="<?= $not_selected ?>"><?= $not_selected ?></option>
            </select>
            <div class="flex-btn">
               <input type="submit" value="update" class="btn" name="update_payment">
               <a href="placed_orders.php?delete=<?= $fetch_orders['order_id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
            </div>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>

      </div>

   </section>

   <script src="../js/admin_script.js"></script>

</body>
</html>