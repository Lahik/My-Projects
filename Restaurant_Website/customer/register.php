<?php 
    include("../db/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
    } 


    if(isset($_POST["register"])) {

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_STRING);
        $pass = sha1($_POST['password']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);
        $cpass = sha1($_POST['confirm_password']);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
        $select_user->execute([$email, $number]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);

        if($select_user->rowCount() > 0){
            $message[] = 'This email or number is already registered!';
        }else{
            if($pass != $cpass){
                $message[] = "Password doesn't match!";
            }else{
                $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
                $insert_user->execute([$name, $email, $number, $cpass]);
                $confirm_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
                $confirm_user->execute([$email, $pass]);
                $row = $confirm_user->fetch(PDO::FETCH_ASSOC);

                if($confirm_user->rowCount() > 0){
                    $_SESSION["id"] = $row["id"];
                    header('location:home.php');
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
    <title>Register</title>
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>
    <section class="form-container"> 

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post"><!-- $_SERVER["PHP_SELF"] is calling the same file itself but it is vulnerable to cross-site scripting(XSS) that's why it's sorrounded with htmlspecialchars()-->
            <h3>Register</h3>
            <input type="text" name="name" required placeholder="Enter your name"
            class="box" maxlength="50">
            <input type="email" name="email" required placeholder="Enter your email"
            class="box" maxlength="50">
            <input type="number" name="number" required placeholder="enter your number" class="box" min="0" max="9999999999" maxlength="10">
            <input type="password" minlength="4" name="password" required placeholder="Enter your password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" minlength="4" name="confirm_password" required placeholder="Confirm your password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" name="register" value="Register" class="btn">    
            <p>Already have an account? <a href="login.php">login</a></p>
        </form>

    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>
    
</body>
</html>
<?php include("../components/footer.php"); ?>