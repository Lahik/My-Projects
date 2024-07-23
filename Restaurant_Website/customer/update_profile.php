<?php 
    include("../db/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
        header('location: home.php');
    } 

    if(isset($_POST['update'])){

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_STRING);

        if(!empty($name)){
            $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
            $update_name->execute([$name, $user_id]);
        }
    
        if(!empty($email)){
            $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
            $select_email->execute([$email]);
            if($select_email->rowCount() > 0){
                $message[] = 'email already taken!';
            }else{
                $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
                $update_email->execute([$email, $user_id]);
            }
        }
    
        if(!empty($number)){
            $select_number = $conn->prepare("SELECT * FROM `users` WHERE number = ?");
            $select_number->execute([$number]);
            if($select_number->rowCount() > 0){
                $message[] = 'number already taken!';
            }else{
                $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
                $update_number->execute([$number, $user_id]);
            }
        }
        
        $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

        $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
        $select_prev_pass->execute([$user_id]);
        $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
        $prev_pass = $fetch_prev_pass['password'];

        $old_pass = sha1($_POST['current_password']);
        $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
        $new_pass = sha1($_POST['new_password']);
        $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
        $confirm_pass = sha1($_POST['confirm_new_password']);
        $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);
     
        if($old_pass != $empty_pass){
           if($old_pass != $prev_pass){
              $message[] = 'Current password is not matching!';
           }elseif($new_pass != $confirm_pass){
              $message[] = 'Confirm password are not matching!';
           }else{
              if($new_pass != $empty_pass){
                 $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
                 $update_pass->execute([$confirm_pass, $user_id]);
                 $message[] = 'Profile updated successfully!';
              }else{
                 $message[] = 'Please enter a new password!';
              }
           }
        }  
     
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update profile</title>
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>    
    <section class="form-container">

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h3>update profile</h3>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name'] ?>"
            class="box" maxlength="50">
            <input type="email" name="email" placeholder="<?= $fetch_profile['email'] ?>"
            class="box" maxlength="50">
            <input type="number" name="number" placeholder="<?= $fetch_profile['number'] ?>"  
            class="box" max="9999999999">
            <input type="password" name="current_password" required placeholder="enter your current password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_password" required placeholder="enter your new password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="confirm_new_password" required placeholder="confirm your new password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" name="update" value="update now" class="btn">    
        </form>
    
    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>

</body>
</html>
<?php include("../components/footer.php"); ?>