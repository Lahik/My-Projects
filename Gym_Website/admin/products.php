<?php
   include('../Components/toast.php');
   include('../Database/database.php');
   session_start();

   if(isset($_SESSION["admin_id"])) {
      $admin_id = $_SESSION["admin_id"];
   }else if(isset($_COOKIE['remember_admin'])) {
      $admin_id = $_COOKIE['remember_admin'];
   }else {
      $admin_id = '';
      header('location: admin_login.php');
   } 

   if(isset($_POST['add_product'])){
      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
      $price = filter_input(INPUT_POST, "price", FILTER_SANITIZE_SPECIAL_CHARS);
      $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);

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

      try{
      $select_products = $conn->prepare("SELECT * FROM products WHERE name = ?");
      $select_products->execute([$name]);

      if($select_products->rowCount() > 0){
         echo '<script>show_toast("product name already exists" ,"error");</script>';
      }else{
         if($image_size > 2000000){
            echo '<script>show_toast("image size is too large" ,"invalid");</script>';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);

            $insert_product = $conn->prepare("INSERT INTO products(name, category, price, image) VALUES(?,?,?,?)");
            $insert_product->execute([$name, $category, $price, $unique_filename]);

            echo '<script>show_toast("new product added!" ,"success");</script>';
         }
      }
      }catch (PDOException $e) {
         echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
      }
   }

   if(isset($_GET['delete'])){
      try{
      $delete_id = $_GET['delete'];
      $delete_image = $conn->prepare("SELECT * FROM products WHERE id = ?");
      $delete_image->execute([$delete_id]);
      
      //deleting image from the folder
      $fetch_delete_image = $delete_image->fetch();
      unlink('../uploaded images/'.$fetch_delete_image['image']);

      //deleting product
      $delete_product = $conn->prepare("DELETE FROM products WHERE id = ?");
      $delete_product->execute([$delete_id]);

      //deleting from wishlist
      $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE item_id = ?");
      $delete_wishlist->execute([$delete_id]);

      echo '<script>show_toast("item deleted" ,"success");</script>';
      }catch (PDOException $e) {
         echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
      }
   }

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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="add-products">

      <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data">
         <h3>add product</h3>
         <input type="text" required placeholder="Enter product name" name="name" maxlength="50" class="box">
         <input type="number" min="50" max="99999" required placeholder="Enter product price" name="price" onkeypress="if(this.value.length == 5) return false;" class="box">
         <select name="category" class="box" required>
            <option value="" disabled selected>Select category --</option>
            <option value="fitness equipments">Fitness Equipments</option>
            <option value="accessories">Accessories</option>
            <option value="supplements and nutrition">Supplements & Nutrition</option>
            <option value="gadgets">Gadgets</option>
            <option value="gym merchandise">Gym Merchandise</option>
         </select>
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
         try{
         $show_products = $conn->prepare($query);
         $show_products->execute();
         
         if($show_products->rowCount() > 0){
            while($fetch_products = $show_products->fetch()){  
      ?>
      <div class="box">
         <img src="../uploaded images/<?= $fetch_products['image']; ?>" alt="">
         <div>
            <div class="category"><?= $fetch_products['category']; ?></div>
         </div>
         <div>
            <div class="name"><?= $fetch_products['name']; ?></div>
            <div class="price"><span>Rs </span><?= $fetch_products['price']; ?></div>
         </div>
         <div class="flex-btn">
            <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
            <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
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
      }catch (PDOException $e) {
         echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
      }
      ?>
      </div>

   </section>

   <script src="../JS/admin_script.js"></script>

</body>
</html>