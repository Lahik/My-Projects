<?php
   include('../Components/toast.php');
   include('../Database/database.php');
   session_start();

   if(isset($_SESSION["admin_id"])) {
      header('location: admin_home.php');
   }

   if(isset($_COOKIE['remember_admin'])) {
      $admin_id = $_COOKIE['remember_admin'];
      header('location: admin_home.php');
   }

   if(isset($_POST['submit'])){

      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

      try {
      $select_admin = $conn->prepare("SELECT * FROM admin WHERE name = ?");
      $select_admin->execute([$name]);
      
      if($select_admin->rowCount() > 0){
         $fetch_admin = $select_admin->fetch();
         $db_password = $fetch_admin['password'];

         if(password_verify($password, $db_password)) {
            $_SESSION["admin_id"] = $fetch_admin["id"];

            if(isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
               $cookie_validity = 60*60*24*30;
               setcookie('remember_admin', $fetch_admin['id'], time() + $cookie_validity, "/");
            } else {
               setcookie('remember_admin', $fetch_admin['id'], time() + -1, "/");
            }
            header('location: admin_home.php');
         }else {
            echo '<script>show_toast("incorrect credentials" ,"error");</script>';
         }
      }else{
         echo '<script>show_toast("incorrect credentials" ,"error");</script>';
      }
      }catch (PDOException $e) {
         echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
      }
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

   <section class="form-container">

      <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
         <h3>admin login</h3>
         <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="password" maxlength="20" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <p><input type="checkbox" name="remember_me"> Remember me</p>
         <input type="submit" value="login" name="submit" class="btn">
      </form>

   </section>

</body>
</html>