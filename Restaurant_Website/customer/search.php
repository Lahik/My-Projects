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

    if(isset($_POST["search-btn"]) || isset($_POST["search-box"])) {
        $_SESSION['selected_category'] = isset($_POST['category']) ? $_POST['category'] : '';
        $_SESSION['selected_cuisine'] = isset($_POST['cuisine']) ? $_POST['cuisine'] : '';
        $_SESSION['selected_sort'] = isset($_POST['sort']) ? $_POST['sort'] : 'latest';
    }
    $selected_category = isset($_SESSION['selected_category']) ? $_SESSION['selected_category'] : '';
    $selected_cuisine = isset($_SESSION['selected_cuisine']) ? $_SESSION['selected_cuisine'] : '';
    $selected_sort = isset($_SESSION['selected_sort']) ? $_SESSION['selected_sort'] : 'latest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>    
    <section class="search-form">

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="search-box">
                <input type="text" name="search-box" placeholder="search here..." value="<?= isset($_POST['search-box']) ? htmlspecialchars($_POST['search-box']) : '' ?>" class="box">
                <button type="submit" name="search-btn" class="fas fa-search"></button>
            </div>

            <div class="sorting">

                <div class="sort">
                    <label>Sort by</label>
                    <select name="sort">
                        <option value="latest" <?= ($selected_sort == 'latest') ? 'selected' : ''; ?> id="sort">Latest products</option>
                        <option value="low_to_high" <?= ($selected_sort == 'low_to_high') ? 'selected' : ''; ?> id="sort">Price (low -> high)</option>
                        <option value="high_to_low" <?= ($selected_sort == 'high_to_low') ? 'selected' : ''; ?> id="sort">Price (high -> low)</option>
                    </select>
                </div>

                <div class="category-cuisine">

                    <div class="category">
                        <label>Sort by Category</label>
                        <select name="category">
                            <option <?= (empty($selected_category)) ? 'selected' : ''; ?> value="">all</option>
                            <option <?= ($selected_category == 'main dish') ? 'selected' : ''; ?> value="main dish">main dish</option>
                            <option <?= ($selected_category == 'fast food') ? 'selected' : ''; ?> value="fast food">fast food</option>
                            <option <?= ($selected_category == 'drinks') ? 'selected' : ''; ?> value="drinks">drinks</option>
                            <option <?= ($selected_category == 'desserts') ? 'selected' : ''; ?> value="desserts">desserts</option>
                        </select>
                    </div>

                    <div class="cuisine">
                        <label>Sort by Cuisine</label>
                        <select name="cuisine">
                            <option <?= (empty($selected_cuisine)) ? 'selected' : ''; ?> value="">all</option>
                        <?php 
                            $select_cuisines = $conn->prepare('SELECT DISTINCT cuisine FROM products');
                            $select_cuisines->execute();
                            while($fetch_cuisines = $select_cuisines->fetch(PDO::FETCH_ASSOC)) {
                                $cuisine = $fetch_cuisines['cuisine'];
                                if($cuisine != '') {
                        ?>
                                <option <?= ($selected_cuisine == $cuisine) ? 'selected' : ''; ?> value="<?= $cuisine ?>"><?= $cuisine ?></option>
                        <?php  
                                }
                            }
                        ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        
    </section>

    <section class="products" style="min-height:100vh; padding-top:0;">
    
        <?php 
        if(isset($_POST["search-btn"]) && !empty($_POST['search-box'])) {
            echo '<h1 class="search-title"><span>Searches for&nbsp;&nbsp; </span>'.$_POST['search-box'].'</h1>';
        }
        ?>

        <div class="box-container">
            <?php 
                if(isset($_POST["search-btn"]) || isset($_POST["search-box"])) {
                    
                    $search_box = $_POST["search-box"]; 
                    $query = ("SELECT * FROM products WHERE name LIKE '%{$search_box}%'");

                    $category = '';
                    $cuisine = '';
                    if(isset($_POST['category']) && !empty($_POST['category'])) {
                        $category = $_POST['category'];
                        $query .= " AND category = '$category'";
                    }
                    if(isset($_POST['cuisine']) && !empty($_POST['cuisine'])) {
                        $cuisine = $_POST['cuisine'];
                        $query .= " AND cuisine = '$cuisine'";
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
                        echo '<div class="empty">No products available for your search!</div>';        
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