<?php 
    include("Components/toast.php");
    include("Database/database.php");
    session_start();

    if(isset($_COOKIE['remember_me']) ||  isset($_SESSION['id'])) {
        header('location: index.php');
    }
    
    if(isset($_POST["login"])) {
        try {
            $number = filter_input(INPUT_POST, "number", FILTER_SANITIZE_SPECIAL_CHARS);
            $pass = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

            $select_user = $conn->prepare("SELECT * FROM users WHERE phone_number = ?");
            $select_user->execute([$number]);
    
            $pattern = "/^07\d{8}$/";
            
            if (!preg_match($pattern, $number)) {
                echo '<script>show_toast("invalid phone number format! Try 07xxxxxxxx" ,"error");</script>';
            }else if($select_user->rowCount() > 0) {
                $fetch_user = $select_user->fetch();
                $db_password = $fetch_user['password'];
    
                if(password_verify($pass, $db_password)) {
                    $_SESSION["id"] = $fetch_user["id"];
    
                    if(isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
                        $cookie_validity = 60*60*24*30;
                        setcookie('remember_user', $fetch_user['id'], time() + $cookie_validity, "/");
                    } else {
                        setcookie('remember_user', $fetch_user['id'], time() + -1, "/");
                    }
                    echo '<script>show_toast("login success" ,"success");</script>';
                    header('location: index.php');
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>
    <div class="form-container">

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h3>Login</h3>
            <input type="text" name="number" required placeholder="Enter your phone number"
            class="box" maxlength="50">
            <input type="password" minlength="4" name="password" required placeholder="Enter your password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <p><input type="checkbox" name="remember_me"> Remember me</p>
            <input type="submit" name="login" value="Login" class="btn">    
            <p>Don't have an account? <a href="register.php">register</a></p>
        </form>

    </div>

    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>