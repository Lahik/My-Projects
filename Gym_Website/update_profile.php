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
        header('location: index.php');
    }

    if(isset($_POST['update'])){
        try{
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $number = filter_input(INPUT_POST, "number", FILTER_SANITIZE_SPECIAL_CHARS);

        if(!empty($name)){
            $update_name = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
            $update_name->execute([$name, $user_id]);
        }
    
        if(!empty($number)){
            $select_number = $conn->prepare("SELECT * FROM users WHERE phone_number = ?");
            $select_number->execute([$number]);
            if($select_number->rowCount() > 0){
                echo '<script>show_toast("this number already taken" ,"error");</script>';
                return;
            }else{
                $update_number = $conn->prepare("UPDATE users SET number = ? WHERE id = ?");
                $update_number->execute([$number, $user_id]);
            }
        }
        
        $select_prev_pass = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $select_prev_pass->execute([$user_id]);
        $fetch_prev_pass = $select_prev_pass->fetch();
        $prev_pass = $fetch_prev_pass['password'];

        $old_pass = filter_input(INPUT_POST, "current_password", FILTER_SANITIZE_SPECIAL_CHARS);
        $new_pass = filter_input(INPUT_POST, "new_password", FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_pass = filter_input(INPUT_POST, "confirm_new_password", FILTER_SANITIZE_SPECIAL_CHARS);
     
        if(!empty($old_pass)) {
            if(password_verify($old_pass, $prev_pass)) {
                if($new_pass !== $confirm_pass) {
                    echo '<script>show_toast("confirm password doesn\'t match" ,"error");</script>';
                }else {
                    if(!empty($new_pass)) {
                        $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
                        $update_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $update_pass->execute([$hashed_password, $user_id]);
                        echo '<script>show_toast("profile updated successfully" ,"success");</script>';
                    }else {
                        echo '<script>show_toast("please Enter a new password" ,"invalid");</script>';
                    }
                }
            }else {
                echo '<script>show_toast("current password is incorrect" ,"error");</script>';
            }
        }else {
            echo '<script>show_toast("please Enter your current password" ,"error");</script>';
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update profile</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

    <section class="form-container">

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h3>update profile</h3>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name'] ?>"
            class="box" maxlength="50">
            <input type="number" name="number" placeholder="<?= $fetch_profile['phone_number'] ?>"  
            class="box" max="9999999999">
            <input type="password" name="current_password" required placeholder="Enter your current password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_password" required placeholder="Enter your new password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="confirm_new_password" required placeholder="Confirm your new password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" name="update" value="update now" class="btn">    
        </form>
    
    </section>

    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>