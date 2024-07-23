<?php 
    include("../db/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
    } 

    include("../components/add_wishlist.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php");?>
    <div class="heading">
        <h3>about us</h3>
    </div>
    
    <section class="about">
        <div class="row">

            <div class="image">
                <img src="../images/about-img.svg" alt="">
            </div>

            <div class="content">
                <h3>Why choose us?</h3>
                <p>At The Outer Clove Restaurant, we make eating out special. Our chefs create delicious dishes using local
                    ingredients. Our place is cozy and comfortable, with a diverse menu that has something for 
                    everyone. We really care about giving you great service, being kind to the environment,
                    and being part of the community. When you eat with us, it's not just a meal, it's a memorable
                    experience. Come join us for tasty food and a good time!</p>
                <a href="menu.php" class="btn">our menu</a>
            </div>
            
        </div>
    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>
    
</body>
</html>
<?php include("../components/footer.php");?>