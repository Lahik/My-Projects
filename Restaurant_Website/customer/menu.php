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
    <title>Menu</title>
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>
    <div class="heading">
        <h3>our menu</h3>
    </div>

    <section class="products">
        <h1 class="title">latest dishes</h1>

        <div class="box-container">
            <?php 
                $select_products = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
                $select_products->execute();
                if($select_products->rowCount() > 0) {
                    while($fetch_products = $select_products->fetch(pdo::FETCH_ASSOC)) {
            ?>
                    <form action="" method="POST" class="box">
                    <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                    <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                    <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                    <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">

                    <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" id="eye-btn" class="fas fa-eye"></a>

                <?php 
                    $is_wishlist_item = $conn->prepare('SELECT * FROM wishlist WHERE user_id = ? AND food_id = ?');
                    $is_wishlist_item->execute([$user_id, $fetch_products['id']]);
                    $is_wishlist_item->rowCount() > 0 ? $red = 'red' : $red = ''; 
                ?>
                    <button type="submit" name="add_to_wishlist" id="heart-btn" class="fas fa-heart" style="color: <?= $red ?>;"></button>
                    <button type="submit" name="add_to_cart" id="cart-btn" class="fas fa-shopping-cart"></button>

                    <img src="../uploaded images/<?= $fetch_products['image']; ?>" alt="">

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
                    </form>
            <?php   
                }
                }else {
                    echo '<div class="empty">no products added yet!</div>';        
                }
            ?>

        </div>

    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>

</body>
</html>
<?php include("../components/footer.php"); ?>