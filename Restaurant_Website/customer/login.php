<?php 
    include("../db/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
    } 

    if(isset($_POST["login"])) {

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $pass = sha1($_POST['password']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
        $select_user->execute([$email, $pass]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);

        if($select_user->rowCount() > 0){
            $_SESSION["id"] = $row["id"];
            header('location:home.php');
        }else{
            $message[] = 'Incorrect email or password!';
        }
    }   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php");  ?>

    <section class="form-container">

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h3>Login</h3>
            <input type="email" name="email" required placeholder="Enter your email"
            class="box" maxlength="50">
            <input type="password" minlength="4" name="password" required placeholder="Enter your password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" name="login" value="Login" class="btn">    
            <p>Don't have an account? <a href="register.php">register</a></p>
        </form>

    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>

</body>
</html>
<?php include("../components/footer.php"); ?>