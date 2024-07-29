<?php 
    include("Components/toast.php");
    include("Database/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    } else if(isset($_COOKIE['remember_user'])) {
        $user_id = $_COOKIE['remember_user'];
    } else {
        $user_id = '';
        header('location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

    <section class="user-profile">

        <div class="user">
            <?php 
            try{
            ?>
            <img src="images/user-icon.png" alt="">
            <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
            <p><i class="fas fa-phone"></i><span><?= $fetch_profile['phone_number'] ?></span></p>
            <a href="update_profile.php" class="btn">update info</a>

            <p class="address"><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == '')
            {echo "please update your address";}else{echo $fetch_profile['address'];} ?></span></p>

            <a href="update_address.php" class="btn">update address</a>
            <?php 
            }catch (PDOException $e) {
                echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
            }
            ?>
        </div>
    
    </section>

    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>