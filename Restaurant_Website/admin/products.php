<?php

   include('../db/database.php');

   session_start();

   if(isset($_SESSION["admin_id"])) {
      $admin_id = $_SESSION["admin_id"];
   }else {
      $admin_id = '';
      header('location: admin_login.php');
   } 

   if(isset($_POST['add_product'])){

      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
      $price = filter_input(INPUT_POST, "price", FILTER_SANITIZE_SPECIAL_CHARS);
      $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
      $cuisine = filter_input(INPUT_POST, "cuisine", FILTER_SANITIZE_SPECIAL_CHARS);

      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];

      $initial_filename = strtolower(str_replace(' ', '_', $category));
      $image_extension = pathinfo($image, PATHINFO_EXTENSION);

      $counter = 1;
      while (file_exists('../uploaded images/'.$initial_filename.'_'.$counter.'.'.$image_extension)) {
         $counter++;
      }

      $unique_filename = $initial_filename.'_'.$counter.'.'.$image_extension;
      $image_folder = '../uploaded images/'.$unique_filename;

      $select_products = $conn->prepare("SELECT * FROM products WHERE name = ?");
      $select_products->execute([$name]);

      if($select_products->rowCount() > 0){
         $message[] = 'product name already exists!';
      }else{
         if($image_size > 2000000){
            $message[] = 'Image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);

            $insert_product = $conn->prepare("INSERT INTO products(name, cuisine, category, price, image) VALUES(?,?,?,?,?)");
            $insert_product->execute([$name, $cuisine, $category, $price, $unique_filename]);

            $message[] = 'New product added!';
         }

      }

   }

   if(isset($_GET['delete'])){

      $delete_id = $_GET['delete'];
      $delete_image = $conn->prepare("SELECT * FROM products WHERE id = ?");
      $delete_image->execute([$delete_id]);
      
      //deleting image from the folder
      $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded images/'.$fetch_delete_image['image']);

      //deleting product
      $delete_product = $conn->prepare("DELETE FROM products WHERE id = ?");
      $delete_product->execute([$delete_id]);

      //deleting from wishlist
      $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE food_id = ?");
      $delete_wishlist->execute([$delete_id]);

      $message[] = 'Deleted product!';
      header('location: products.php');
   }

   if(isset($_GET['pid'])) {
      $pid = $_GET['pid'];

      $get_product = $conn->prepare('SELECT hero_slider FROM products WHERE id = ?');
      $get_product->execute([$pid]);
      $fetch_product = $get_product->fetch(PDO::FETCH_ASSOC);

      $initial_hero = (bool) $fetch_product['hero_slider'];
      $hero = !$initial_hero;

      $set_hero = $conn->prepare('UPDATE products SET hero_slider = ? WHERE id = ?');
      $set_hero->execute([$hero, $pid]);
      header('location: products.php');
   }


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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="add-products">

      <form action="" method="POST" enctype="multipart/form-data">
         <h3>add product</h3>
         <input type="text" required placeholder="Enter product name" name="name" maxlength="50" class="box">
         <input type="number" min="50" max="99999" required placeholder="Enter product price" name="price" onkeypress="if(this.value.length == 5) return false;" class="box">
         <select name="category" class="box" required>
            <option value="" disabled selected>Select category --</option>
            <option value="main dish">Main dish</option>
            <option value="fast food">Fast food</option>
            <option value="drinks">Drinks</option>
            <option value="desserts">Desserts</option>
         </select>
         <input type="text" placeholder="Enter cuisine type (Optional)" maxlength="50" name="cuisine" class="box">
         <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
         <input type="submit" value="add product" name="add_product" class="btn">
      </form>

   </section>


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

   <section class="show-products" style="padding-top: 0;">

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
            $show_products = $conn->prepare($query);
            $show_products->execute();
         
         if($show_products->rowCount() > 0){
            while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <div class="box">
         <img src="../uploaded images/<?= $fetch_products['image']; ?>" alt="">
         <div class="flex">
            <div class="category"><?= $fetch_products['category']; ?></div>
            <div class="cuisine"><?= $fetch_products['cuisine']; ?></div>
         </div>
         <div class="flex">
            <div class="name"><?= $fetch_products['name']; ?></div>
            <div class="price"><span>Rs </span><?= $fetch_products['price']; ?></div>
         </div>
         <div class="flex-btn">
            <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
            <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
         </div>
         <?php 
            if($fetch_products['hero_slider'] == 1) {
               $btn = 'delete-btn';
               $btn_value = 'Remove from hero-slider';
            }else {
               $btn = 'hero-btn';
               $btn_value = 'Add to hero-slider';
            }
         ?>
         <div class="hero">
            <a href="products.php?pid=<?= $fetch_products['id']; ?>" class="<?= $btn ?>"><?= $btn_value ?></a>
         </div>
      </div>
      <?php
            }
         }else{
            $check_products = $conn->prepare('SELECT * FROM products');
            $check_products->execute();
            if($check_products->rowCount() > 0) {
               echo '<p class="empty">No products available for your search!</p>';
            }else {
               echo '<p class="empty">No products added yet!</p>';
            }
         }
      
      ?>

      </div>

   </section>

   <script src="../js/admin_script.js"></script>

</body>
</html>