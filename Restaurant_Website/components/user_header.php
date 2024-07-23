<?php
include("../db/database.php"); 

if(isset($message)) {
    foreach($message as $m) {
        echo '
        <div class="message">
            <span>'.$m.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
if(isset($_SESSION["id"])) {
    $user_id = $_SESSION["id"];
}else {
    $user_id = '';
}
if(empty($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}
?>
<script src="https://kit.fontawesome.com/9678f11e09.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<link rel="stylesheet" href="../CSS/customer_style.css">

<header class="header">
    <section class="flex">

        <a href="../customer/home.php" class="logo">
            <img src="../images/logo.png" alt="">
        </a>
    
        <?php
            function getCurrentPageClass($page) {
                $currentPage = basename($_SERVER['PHP_SELF']);
                return ($currentPage == $page) ? 'selected' : '';
            }
        ?>
        <nav class="navbar">
            <a href="home.php" class="<?php echo getCurrentPageClass('home.php'); ?>">home</a>
            <a href="menu.php" class="<?php echo getCurrentPageClass('menu.php'); ?>">menu</a>
            <a href="order.php" class="<?php echo getCurrentPageClass('order.php'); ?>">orders</a>
            <a href="reservation.php" class="<?php echo getCurrentPageClass('reservation.php'); ?>">reservation</a>
            <a href="about.php" class="<?php echo getCurrentPageClass('about.php'); ?>">about</a>
            <a href="contact.php" class="<?php echo getCurrentPageClass('contact.php'); ?>">contact</a>
        </nav>


        <div class="icons">
            <?php 
                $user_wishlist_items = $conn->prepare("SELECT * FROM wishlist 
                WHERE user_id = ?");
                $user_wishlist_items->execute([$user_id]);
                $total_user_wishlist_items = $user_wishlist_items->rowCount();


            ?>
            <a href="search.php"><i class="fas fa-search"></i></a>
            <a href="wishlist.php"><i class="fas fa-heart"><sub><span>(<?= $total_user_wishlist_items; ?>)</span></sub></i></a>
            <a href="../customer/cart.php"><i class="fas fa-shopping-cart"><sub><span>(<?= count($_SESSION["cart"]); ?>)</span></sub></i></a>
            <i id="user-btn" class="fa-solid fa-user"></i>
            <i id="menu-btn" class="fa-solid fa-bars"></i>
        </div>

        <div class="profile">
            <?php  
                $select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $select_profile->execute([$user_id]);
                if($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <p class="name" style="text-transform: capitalize;"><?= $fetch_profile['name'] ?></p>  
            <div class="flex">
                <a href="profile.php" class="btn">Profile</a>   
                <a href="../components/user_logout.php" onclick="return confirm ('logout from this website?');" class="delete-btn">logout</a>
            </div>
        <?php 
            }else {
        ?>
                <p class="name">Please login first</p>
                <a href="../customer/login.php" class="btn">login</a>
        <?php 
            } 
        ?>
        </div>
    </section>
</header>   
<script src="../js/script.js"></script>