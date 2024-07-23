<?php

   include('../db/database.php');

   session_start();

   if(isset($_SESSION["staff_id"])) {
      $staff_id = $_SESSION["staff_id"];
   }else {
      $staff_id = '';
      header('location: staff_login.php');
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

   <?php include '../components/staff_header.php' ?>

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


   <section class="show-products">

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