<?php 
    include("Database/database.php");
    
    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    } else if(isset($_COOKIE['remember_user'])) {
        $user_id = $_COOKIE['remember_user'];
    } else {
        $user_id = '';
    }
?>

<header class="header">

    <a href="index.php" class="logo"><span>Champions</span>Gym</a>

    <?php
        function getCurrentPageClass($page) {
            $currentPage = basename($_SERVER['PHP_SELF']);
            return ($currentPage == $page) ? 'selected' : '';
        }
    ?>
    <nav class="navbar">
        <a href="index.php" class="<?= getCurrentPageClass('index.php'); ?>">home</a>
        <a href="index.php#about">about</a>
        <a href="index.php#pricing">pricing</a>
        <a href="membership.php" class="<?= getCurrentPageClass('membership.php'); ?>">membership</a>
        <a href="shop.php" class="<?= getCurrentPageClass('shop.php'); ?>">shop</a>
        <a href="orders.php" class="<?= getCurrentPageClass('orders.php'); ?>">orders</a>
    </nav>

    <div>
        <div id="user-btn" class="fa-solid fa-user"></div>
        <div id="menu-btn" class="fas fa-bars"></div>
    </div>

    <div class="profile">
        <?php
        try{  
            $select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch();
        ?>
        <p class="name" style="text-transform: capitalize;"><?= $fetch_profile['name'] ?></p>  
        <div class="flex">
            <a href="profile.php" class="btn">Profile</a>   
            <a href="Components/user_logout.php" onclick="return confirm ('Logout from this website?');" class="delete-btn">logout</a>
        </div>
    <?php 
        }else {
    ?>
            <p class="name">Please login first</p>
            <a href="login.php" class="btn">login</a>
    <?php 
        } 
    }catch (PDOException $e) {
        echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
    }
    ?>
    </div>
    
</header>