<?php
include("../db/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
    } 

    include("../components/add_wishlist.php");
    include("../components/add_cart.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>

    <section class="quick-view">

        <h1 class="title">quick view</h1>

        <?php 
            $pid = $_GET['pid'];
            $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $select_products->execute([$pid]);
            if($select_products->rowCount() > 0) {
                while($fetch_products = $select_products->fetch(pdo::FETCH_ASSOC)) {
        ?>
                <form action="" method="post" class="box">
                <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">

                <img class="image" src="../uploaded images/<?= $fetch_products['image']; ?>" alt="">

                <div class="cuisine-category">
                    <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"
                    ><?= $fetch_products['category']; ?></a>
                    <a href="cuisine.php?cuisine=<?= $fetch_products['cuisine']; ?>" class="cuisine"
                    ><?= $fetch_products['cuisine']; ?></a>
                </div>

                <div class="name"><?= $fetch_products['name']; ?></div>
                
                <div class="flex">
                    <div class="price"><span>Rs </span><?= $fetch_products['price']; ?></div>
                    <input type="number" name="qty" class="qty" min="1" max="99"
                    value="1" maxlength="2" onkeypress="if(this.value.length == 2) return false;">
                </div>
                <div class="cart-wishlist">
                    <button type="submit" name="add_to_cart" class="cart-btn">add to cart</button>
                    <?php 
                        $select_wishlist = $conn->prepare('SELECT * FROM wishlist WHERE user_id = ? AND food_id = ?');
                        $select_wishlist->execute([$user_id, $fetch_products['id']]);
                        $select_wishlist->rowCount() > 0 ? $wishlist = 'remove from' : $wishlist = 'add to';
                    ?>
                    <button type="submit" name="add_to_wishlist" class="wishlist-btn"><?= $wishlist ?> wishlist</button>
                </div>
                </form>
        <?php   
            }
            }else {
                echo '<div class="empty">no products found!</div>';        
                }
            ?>
    
    </section>
    
    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>

</body>
</html>
<?php include("../components/footer.php"); ?>