<?php

   include('../db/database.php');

   session_start();

   if(isset($_SESSION["admin_id"])) {
      $admin_id = $_SESSION["admin_id"];
   }else {
      $admin_id = '';
      header('location: admin_login.php');
   } 

   if(isset($_POST['update'])){

      $pid = filter_input(INPUT_POST, "pid", FILTER_SANITIZE_SPECIAL_CHARS);
      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
      $price = filter_input(INPUT_POST, "price", FILTER_SANITIZE_SPECIAL_CHARS);
      $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
      $cuisine = filter_input(INPUT_POST, "cuisine", FILTER_SANITIZE_SPECIAL_CHARS);

      $update_product = $conn->prepare("UPDATE products SET name = ?, category = ?, cuisine = ?, price = ? WHERE id = ?");
      $update_product->execute([$name, $category, $cuisine, $price, $pid]);

      $message[] = 'product updated!';

      $current_image = $_POST['current_image'];
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

      if(!empty($image)){
         if($image_size > 2000000){
            $message[] = 'Image size is too large!';
         }else{
            $update_image = $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
            $update_image->execute([$unique_filename, $pid]);
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('../uploaded images/'.$current_image);
            $message[] = 'Image updated!';
         }
      }

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update product</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="update-product">

      <h1 class="heading">update product</h1>

      <?php
         $update_id = $_GET['update'];
         $show_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
         $show_products->execute([$update_id]);
         if($show_products->rowCount() > 0){
            $fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)
      ?>
            <form action="" method="POST" enctype="multipart/form-data">
               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
               <input type="hidden" name="current_image" value="<?= $fetch_products['image']; ?>">

               <img src="../uploaded images/<?= $fetch_products['image']; ?>" alt="">

               <span>Name</span>
               <input type="text" required placeholder="Enter product name" name="name" maxlength="50" class="box" value="<?= $fetch_products['name']; ?>">
               <span>Price</span>
               <input type="number" min="50" max="99999" required placeholder="Enter product price" name="price" onkeypress="if(this.value.length == 5) return false;" class="box" value="<?= $fetch_products['price']; ?>">

               <span>Category</span>
               <select name="category" class="box" required>
                  <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
                  <option value="main dish">main dish</option>
                  <option value="fast food">fast food</option>
                  <option value="drinks">drinks</option>
                  <option value="desserts">desserts</option>
               </select>
               <span>Cuisine</span>
               <input type="text" placeholder="Enter cuisine type (Optional)" name="cuisine" maxlength="50" class="box" value="<?= $fetch_products['cuisine']; ?>">
               <span>Image</span>
               <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">

               <div class="flex-btn">
                  <input type="submit" value="update" class="btn" name="update">
                  <a href="products.php" class="option-btn">go back</a>
               </div>
            </form>
      <?php
            
         }else{
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>

   </section>

   <script src="../js/admin_script.js"></script>

</body>
</html>