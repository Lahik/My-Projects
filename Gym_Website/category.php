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
        header('location: login.php');
    }

    if(empty($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }
    
    include("components/add_product.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

    <section class="products">
        <?php $category = $_GET['category']; ?>
        <h1 class="title"><?= $category ?></h1>

        <div class="box-container">
            <?php 
            try{
                $select_products = $conn->prepare("SELECT * FROM products WHERE category = ?");
                $select_products->execute([$category]);
                if($select_products->rowCount() > 0) {
                    while($fetch_products = $select_products->fetch()) {
            ?>
                    <form action="" method="post" class="box">
                    <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                    <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                    <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                    <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
                    
                    <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" id="eye-btn" class="fas fa-eye"></a>
                <?php 
                    $is_wishlist_item = $conn->prepare('SELECT * FROM wishlist WHERE user_id = ? AND item_id = ?');
                    $is_wishlist_item->execute([$user_id, $fetch_products['id']]);
                    $is_wishlist_item->rowCount() > 0 ? $red = 'red' : $red = ''; 
                ?>
                    <button type="submit" name="add_to_wishlist" id="heart-btn" class="fas fa-heart" style="color: <?= $red ?>;"></button>
                    <button type="submit" name="add_to_cart" id="cart-btn" class="fas fa-shopping-cart"></button>

                    <img src="uploaded images/<?= $fetch_products['image']; ?>" alt="">

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