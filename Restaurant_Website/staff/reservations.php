<?php
   include('../db/database.php');

   session_start();

   if(isset($_SESSION["staff_id"])) {
      $staff_id = $_SESSION["staff_id"];
   }else {
      $staff_id = '';
      header('location: staff_login.php');
   } 

   if(isset($_POST['update_status'])){
      $reservation_id = $_POST['reservation_id'];
      $status = $_POST['reservation_status'];

      if($status == 'approve') {
         $status = 'approved';
      }else if($status == 'reject') {
         $status = 'rejected';
      }

      $update_status = $conn->prepare("UPDATE reservation SET status = ? WHERE id = ?");
      $update_status->execute([$status, $reservation_id]);
      $message[] = 'payment status updated!';
   }

   if(isset($_GET['delete'])) {
      $delete_id = $_GET['delete'];
      $delete_reservation = $conn->prepare("DELETE FROM reservation WHERE id = ?");
      $delete_reservation->execute([$delete_id]);
      header('location: reservations.php');
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reservation</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

   <?php include '../components/staff_header.php' ?>

   <section class="placed-orders">

      <h1 class="heading">placed orders</h1>

      <div class="box-container">

      <?php
        $select_reservations = $conn->prepare("SELECT * FROM reservation");
        $select_reservations->execute();
        if($select_reservations->rowCount() > 0){
        while($fetch_reservations = $select_reservations->fetch(PDO::FETCH_ASSOC)){
      ?>
    <div class="box">
        <p> Reservation No : <span><?= $fetch_reservations['id']; ?></span> </p>
        <p> User ID : <span><?= $fetch_reservations['user_id']; ?></span> </p>
        <p> Date and Time : <span><?= $fetch_reservations['date_time']; ?></span> </p>
        <p> Name : <span><?= $fetch_reservations['name']; ?></span> </p>
        <p> Number : <span><?= $fetch_reservations['number']; ?></span> </p>
        <p> No of people : <span>Adults(<?= $fetch_reservations['adults']?>)&nbsp;&nbsp;
                        <?= ($fetch_reservations['children'] > 0) ? 
                        "Children(".$fetch_reservations['children'].")" : ''; ?></span></p>
        <p> Comments : <span><?= $fetch_reservations['comments']; ?></span> </p>
    <?php 
        if($fetch_reservations['status'] == 'pending') {
            $first_option = 'pending';
            $second_option = 'approve';
            $third_option = 'reject';
        }else if($fetch_reservations['status'] == 'approved') {
            $first_option = 'approved';
            $second_option = 'pending';
            $third_option = 'reject';
        }else if($fetch_reservations['status'] == 'rejected') {
            $first_option = 'rejected';
            $second_option = 'approve';
            $third_option = 'pending';
        }
    ?>
        <form action="" method="POST">
            <input type="hidden" name="reservation_id" value="<?= $fetch_reservations['id']; ?>">
            <select name="reservation_status" class="drop-down">
                <option style=";" value="<?= $first_option ?>" selected><?= $first_option ?></option>
                <option style=";" value="<?= $second_option ?>"><?= $second_option ?></option>
                <option style=";" value="<?= $third_option ?>"><?= $third_option ?></option>
            </select>

            <div class="flex-btn">
               <input type="submit" value="update" class="btn" name="update_status">
               <a href="reservations.php?delete=<?= $fetch_reservations['id']; ?>" class="delete-btn" onclick="return confirm('delete this reservation?');">delete</a>
            </div>
         </form>
      </div>
      <?php
        }
      }else{
         echo '<p class="empty">No reservations placed yet!</p>';
      }
      ?>

      </div>

   </section>

   <script src="../js/admin_script.js"></script>

</body>
</html>