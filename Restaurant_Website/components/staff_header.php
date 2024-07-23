<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">

   <section class="flex">

      <a href="../staff/staff_home.php" class="logo">Staff<span>Panel</span></a>

      <?php
         function getCurrentPageClass($page) {
               $currentPage = basename($_SERVER['PHP_SELF']);
               return ($currentPage == $page) ? 'selected' : '';
         }
      ?>
      <nav class="navbar">
         <a href="staff_home.php" class="<?php echo getCurrentPageClass('staff_home.php'); ?>">home</a>
         <a href="products.php" class="<?php echo getCurrentPageClass('products.php'); ?>">products</a>
         <a href="placed_orders.php" class="<?php echo getCurrentPageClass('placed_orders.php'); ?>">orders</a>
         <a href="reservations.php" class="<?php echo getCurrentPageClass('reservations.php'); ?>">Reservations</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM staff WHERE id = ?");
            $select_profile->execute([$staff_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
         <a href="../components/staff_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
      </div>

   </section>

</header>