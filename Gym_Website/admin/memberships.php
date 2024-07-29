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

   function update_plans($plan, $amount, $conn) {
      try{
         $update_plan = $conn->prepare("UPDATE membership_plan SET amount = ? WHERE plan = ?");
         $update_plan->execute([$amount, $plan]);
         echo '<script>show_toast("Updated '. $plan .' plan", "success");</script>';
      }catch(PDOException $e) {
         echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
      }
   }

   if(isset($_POST['confirm_password'])) {
      $password = $_POST['password'];
      $select_admin = $conn->prepare("SELECT password FROM admin WHERE id = ?");
      $select_admin->execute([$admin_id]);
      $fetch_admin = $select_admin->fetch();
      $db_password = $fetch_admin['password'];
      
      if(password_verify($password, $db_password)) {
         
         if(!empty($_SESSION['plan_details']['basic'])) {
            update_plans('basic', $_SESSION['plan_details']['basic'], $conn);   
         }
   
         if(!empty($_SESSION['plan_details']['standard'])) {
            update_plans('standard', $_SESSION['plan_details']['standard'], $conn);
         }
   
         if(!empty($_SESSION['plan_details']['premium'])) {
            update_plans('premium', $_SESSION['plan_details']['premium'], $conn);
         }

         unset($_SESSION['plan_details']);
      }else {
         echo '<script>show_toast("Incorrect password", "error")</script>';
      }
   }


   if(isset($_POST['update_plan']) && 
      (isset($_POST['basic']) && $_POST['basic'] !== '' || isset($_POST['standard']) && $_POST['standard'] !== '' || isset($_POST['premium']) && $_POST['premium'] !== '')) {

      $_SESSION['plan_details'] = array();

      if(!empty($_POST['basic'])) {
         $_SESSION['plan_details']['basic'] = $_POST['basic'];
      }
     
      if(!empty($_POST['standard'])) {
         $_SESSION['plan_details']['standard'] = $_POST['standard'];
      }
     
      if(!empty($_POST['premium'])) {
         $_SESSION['plan_details']['premium'] = $_POST['premium'];
      }

      echo '
      <div class="password-confirmation">
            <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post">
               <i class="fas fa-times" onclick="removeParent(this)"></i>
               <h3>Password Confirmation</h3>
               <input type="password" required placeholder="Enter your password" name="password" class="box">  
               <input type="submit" class="btn" name="confirm_password">
            </form>
      </div>
   
      <script>
            function removeParent(element) {
               element.parentElement.parentElement.remove();
            }
      </script>';
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Memberships</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="update-membership">
      <?php 
      try{
      $select_plan = $conn->prepare("SELECT * FROM membership_plan");
      $select_plan->execute();
      while($fetch_plan = $select_plan->fetch()) {
         switch($fetch_plan['plan']) {
            case 'basic':
               $basic = $fetch_plan['amount'];
               break;
            case 'standard':
               $standard = $fetch_plan['amount'];
               break;
            case 'premium':
               $premium = $fetch_plan['amount'];
               break;
         }
      }
      ?>
      <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
         <h3>Membership plans</h3>
         <label for="">Basic plan <i class="fas fa-check-circle"></i></label>
         <input type="number" min="1000" max="99999" placeholder="<?= $basic ?>" name="basic" onkeypress="if(this.value.length == 5) return false;" class="box">
         <label for="">Standard plan <i class="fas fa-star"></i></label>
         <input type="number" min="1000" max="99999" placeholder="<?= $standard ?>" name="standard" onkeypress="if(this.value.length == 5) return false;" class="box">
         <label for="">Premium plan <i class="fas fa-crown"></i></label>
         <input type="number" min="1000" max="99999" placeholder="<?= $premium ?>" name="premium" onkeypress="if(this.value.length == 5) return false;" class="box">
         <input type="submit" value="Update" name="update_plan" class="btn">
      </form>
      <?php 
      }catch(PDOException $e) {
         echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
      }
      ?>
   </section>

   <section class="table-container">
   <?php 
   try{
   $select_membership = $conn->prepare("SELECT * FROM membership ORDER BY start DESC");
   $select_membership->execute();
   if($select_membership->rowCount() > 0) {
   ?>
      <div class="table">
         <div class="table-header">
               <h1>All Membership Details</h1>
         </div>
         <div class="table-body">
            <table>
               <thead>
                  <tr>
                     <th>User ID</th>
                     <th>Name</th>
                     <th>Subscription plan</th>
                     <th>From</th>
                     <th>To</th>
                  </tr>
               </thead>
               <tbody>
               <?php 
                  while($fetch_membership = $select_membership->fetch()) {
                     $logo = ($fetch_membership['plan'] == 'basic') ? 'check-circle' : (($fetch_membership['plan'] == 'standard') ? 'star' : 'crown');
                     $select_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
                     $select_user->execute([$fetch_membership['user_id']]);
                     $fetch_user = $select_user->fetch();
               ?>
                  <tr>
                     <td><?= $fetch_membership['user_id'] ?></td>
                     <td class="name"><?= $fetch_user['name'] ?></td>
                     <td>
                        <p class="plan <?= $fetch_membership['plan'] ?>"><?= $fetch_membership['plan'] ?> <i class="fas fa-<?= $logo ?>"></i></p>
                     </td>
                     <td><?= $fetch_membership['start'] ?></td>
                     <td><?= $fetch_membership['end'] ?></td>
                  </tr>
               <?php 
                  }
               ?>
               </tbody>
            </table>
         </div>
      </div>
   <?php
   }else {
   ?>
      <p class="empty">There are no membership subscriptions yet!</p>
   <?php     
   }
   }catch (PDOException $e) {
      echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
   }
   ?>
   </section>

   <script src="../JS/admin_script.js"></script>

</body>
</html>