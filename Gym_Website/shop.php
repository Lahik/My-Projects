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
    }

    if(empty($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }

    include("components/add_product.php");

    if(isset($_POST["search-btn"]) || isset($_POST["search-box"])) {
        $_SESSION['selected_category'] = isset($_POST['category']) ? $_POST['category'] : '';
        $_SESSION['selected_sort'] = isset($_POST['sort']) ? $_POST['sort'] : 'latest';
    }
    $selected_category = isset($_SESSION['selected_category']) ? $_SESSION['selected_category'] : '';
    $selected_sort = isset($_SESSION['selected_sort']) ? $_SESSION['selected_sort'] : 'latest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

    <section class="search-form">

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="search-box">
                <input type="text" name="search-box" placeholder="Search here..." value="<?= isset($_POST['search-box']) ? htmlspecialchars($_POST['search-box']) : '' ?>" class="box">
                <button type="submit" name="search-btn" class="fas fa-search"></button>
            </div>
                <?php 
                try{
                    $user_wishlist_items = $conn->prepare("SELECT * FROM wishlist 
                    WHERE user_id = ?");
                    $user_wishlist_items->execute([$user_id]);
                    $total_user_wishlist_items = $user_wishlist_items->rowCount();
                ?>
            <div class="sorting">
                <div class="sort">
                    <label>Sort by</label>
                    <select name="sort">
                        <option value="latest" <?= ($selected_sort == 'latest') ? 'selected' : ''; ?> id="sort">Latest products</option>
                        <option value="low_to_high" <?= ($selected_sort == 'low_to_high') ? 'selected' : ''; ?> id="sort">Price (low -> high)</option>
                        <option value="high_to_low" <?= ($selected_sort == 'high_to_low') ? 'selected' : ''; ?> id="sort">Price (high -> low)</option>
                    </select>
                </div>
                
                <div class="category">
                    <label>Sort by Category</label>
                    <select name="category">
                        <option <?= (empty($selected_category)) ? 'selected' : ''; ?> value="">all</option>
                        <option <?= ($selected_category == 'fitness equipments') ? 'selected' : ''; ?> value="fitness equipments">fitness equipments</option>
                        <option <?= ($selected_category == 'accessories') ? 'selected' : ''; ?> value="accessories">accessories</option>
                        <option <?= ($selected_category == 'supplements and nutrition') ? 'selected' : ''; ?> value="supplements and nutrition">supplements & nutrition</option>
                        <option <?= ($selected_category == 'gadgets') ? 'selected' : ''; ?> value="gadgets">gadgets</option>
                        <option <?= ($selected_category == 'gym merchandise') ? 'selected' : ''; ?> value="gym merchandise">gym merchandise</option>
                    </select>
                </div>
            </div>

            <div class="cart-wish">
                <a href="wishlist.php"><i class="fas fa-heart"><sub><span>(<?= $total_user_wishlist_items; ?>)</span></sub></i></a>
                <a href="cart.php"><i class="fas fa-shopping-cart"><sub><span>(<?= count($_SESSION["cart"]); ?>)</span></sub></i></a>
            </div>
        </form>
        <?php 
        }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
        }
        ?>
    </section>


    <section class="products">

        <?php 
            if(isset($_POST["search-btn"]) && !empty($_POST['search-box'])) {
            echo '<h1 class="search-title"><span>Searches for&nbsp;&nbsp; </span>'.$_POST['search-box'].'</h1>';
            }
        ?>

        <div class="box-container">
            <?php 
            try{
                if(isset($_POST["search-btn"]) || isset($_POST["search-box"])) {
                    
                    $search_box = $_POST["search-box"]; 
                    $query = ("SELECT * FROM products WHERE name LIKE '%{$search_box}%'");

                    $category = '';

                    if(isset($_POST['category']) && !empty($_POST['category'])) {
                        $category = $_POST['category'];
                        $query .= " AND category = '$category'";
                    }

                    if(isset($_POST['sort'])) {
                        if($_POST['sort'] == 'low_to_high') {
                            $query .= ' ORDER BY price ASC';
                        }else if($_POST['sort'] == 'high_to_low') {
                            $query .= ' ORDER BY price DESC';
                        }else {
                            $query .= ' ORDER BY id DESC';
                        }
                    }
                }else {
                    $query = ('SELECT * FROM products ORDER BY id DESC');
                }
                $select_products = $conn->prepare($query);
                $select_products->execute();
                
                if($select_products->rowCount() > 0) {
                    while($fetch_products = $select_products->fetch()) {
            ?>
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" class="box">
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

                    <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"
                    ><?= $fetch_products['category']; ?></a>

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