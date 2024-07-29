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
        header("location: login.php");
    }

    if(isset($_POST['delete'])) {
        try{
        $product_id = $_POST['pid_delete'];

        $delete_query = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND item_id = ?");
        $delete_query->execute([$user_id, $product_id]);

        $delete_row_count = $delete_query->rowCount();

        if($delete_row_count > 0) {
            echo '<script>show_toast("product removed successfully" ,"success");</script>';
        } else {
            echo '<script>show_toast("failed to remove product" ,"error");</script>';
        }
        }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
        }
    }

    if(isset($_POST['delete_all'])) {
        try{
        $delete_query = $conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
        $delete_query->execute([$user_id]);
        echo '<script>show_toast("all products removed successfully" ,"success");</script>';
        }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

    <section class="products">

        <div class="box-container">
            <?php
            try{
                $wishlist_products = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
                $wishlist_products->execute([$user_id]);

                if($wishlist_products->rowCount() > 0) {
                    while($fetch_wishlist_products = $wishlist_products->fetch()) {
                        $product_id = $fetch_wishlist_products['item_id'];

                        $products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                        $products->execute([$product_id]);
                        $fetch_products = $products->fetch()
            ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="box">
                            <input type="hidden" name="pid_delete" value="<?= $product_id; ?>">

                            <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" id="eye-btn" class="fas fa-eye"></a>

                            <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('delete this item?');"></button>
                            <img src="uploaded images/<?= $fetch_products['image']; ?>" alt="">

                            <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"
                            ><?= $fetch_products['category']; ?></a>

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
            }catch (PDOException $e) {
                echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
            }    
            ?>
        </div>
        
        <?php 
        if($wishlist_products->rowCount() > 0) {
        ?>
            <div class="more-btn">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <button type="submit" class="delete-btn" name="delete_all" onclick="return confirm('Remove all from wishlist?');">remove all</button>
                </form>
            </div>
        <?php
        }
        ?>
    </section>

    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>