<?php

   include('../db/database.php');

   session_start();

   if(isset($_POST['submit'])){

      $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
      $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

      $select_admin = $conn->prepare("SELECT * FROM admin WHERE name = ?");
      $select_admin->execute([$name]);
      
      if($select_admin->rowCount() > 0){
         $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
         $db_password = $fetch_admin['password'];

         if(password_verify($password, $db_password)) {
            $_SESSION['admin_id'] = $fetch_admin['id'];
            header('location: admin_home.php');
         }else {
            $message[] = 'Incorrect username or password!';
         }
      }else{
         $message[] = 'Incorrect username or password!';
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

   <?php
      if(isset($message)){
         foreach($message as $message){
            echo '
            <div class="message">
               <span>'.$message.'</span>
               <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
         }
      }
   ?>

   <section class="form-container">

      <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
         <h3>admin login</h3>
         <p>Default username = <span>icbt</span> & password = <span>icbt</span></p>
         <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="password" maxlength="20" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="login" name="submit" class="btn">
      </form>

   </section>

</body>
</html>