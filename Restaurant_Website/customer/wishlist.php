<?php 
    include("../db/database.php");
    session_start();
    
    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    } else {
        $user_id = '';
        header("location:login.php");
    } 

    if(isset($_POST['delete'])) {

        $user_id = $_SESSION["id"];
        $product_id = $_POST['pid_delete'];

        $delete_query = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND food_id = ?");
        $delete_query->execute([$user_id, $product_id]);

        $delete_row_count = $delete_query->rowCount();

        if($delete_row_count > 0) {
            $message[] = "Product removed successfully!";
        } else {
            $message[] = "Failed to remove product!";
        }
    }

    if(isset($_POST['delete_all'])) {

        $delete_query = $conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
        $delete_query->execute([$user_id]);

        $message[] = "All products removed successfully!";
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>
    <section class="products">
        <h1 class="title">your wishlist</h1>

        <div class="box-container">
            <?php 
                $wishlist_products = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
                $wishlist_products->execute([$user_id]);

                if($wishlist_products->rowCount() > 0) {
                    while($fetch_wishlist_products = $wishlist_products->fetch(PDO::FETCH_ASSOC)) {
                        $product_id = $fetch_wishlist_products['food_id'];

                        $products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                        $products->execute([$product_id]);
                        $fetch_products = $products->fetch(PDO::FETCH_ASSOC)
            ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="box">
                            <input type="hidden" name="pid_delete" value="<?= $product_id; ?>">

                            <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" id="eye-btn" class="fas fa-eye"></a>
                            <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('delete this item?');"></button>
                            <img src="../uploaded images/<?= $fetch_products['image']; ?>" alt="">

                            <div class="cuisine-category">
                                <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
                                <a href="cuisine.php?cuisine=<?= $fetch_products['cuisine']; ?>" class="cuisine"><?= $fetch_products['cuisine']; ?></a>
                            </div>

                            <div class="name"><?= $fetch_products['name']; ?></div>

                            <div class="flex">
                                <div class="price"><span>Rs </span><?= $fetch_products['price']; ?></div>
                            </div>
                        </form>
            <?php  
                    }
                } else {
                    echo '<div class="empty">No products added to wishlist yet!</div>';
                }
            ?>
        </div>
        
        <div class="more-btn">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button type="submit" class="delete-btn" name="delete_all" onclick="return confirm('Remove all from wishlist?');">remove all</button>
            </form>
        </div>
    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>
</body>
</html>
<?php include("../components/footer.php"); ?>
