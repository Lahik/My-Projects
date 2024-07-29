<header class="header">

   <section class="flex">

      <a href="../admin/admin_home.php" class="logo">Admin<span>Panel</span></a>

      <?php
         function getCurrentPageClass($page) {
               $currentPage = basename($_SERVER['PHP_SELF']);
               return ($currentPage == $page) ? 'selected' : '';
         }
      ?>
      <nav class="navbar">
         <a href="admin_home.php" class="<?php echo getCurrentPageClass('admin_home.php'); ?>">home</a>
         <a href="products.php" class="<?php echo getCurrentPageClass('products.php'); ?>">products</a>
         <a href="memberships.php" class="<?php echo getCurrentPageClass('memberships.php'); ?>">memberships</a>
         <a href="placed_orders.php" class="<?php echo getCurrentPageClass('placed_orders.php'); ?>">orders</a>
         <a href="admin_accounts.php" class="<?php echo getCurrentPageClass('admin_accounts.php'); ?>">admins</a>
         <a href="user_accounts.php" class="<?php echo getCurrentPageClass('user_accounts.php'); ?>">users</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
         try{
            $select_profile = $conn->prepare("SELECT * FROM admin WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch();
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         <?php 
         }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
         }
         ?>
      </div>

   </section>

</header>