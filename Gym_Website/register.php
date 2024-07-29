<?php 
    include("Components/toast.php");
    include("Database/database.php");
    session_start();

    if(isset($_COOKIE['remember_me']) ||  isset($_SESSION['id'])) {
        header('location: index.php');
    }

    if(isset($_POST["register"])) {
        try {
            $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
            $number = filter_input(INPUT_POST, "number", FILTER_SANITIZE_SPECIAL_CHARS);
            $pass = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
            $cpass = filter_input(INPUT_POST, "confirm_password", FILTER_SANITIZE_SPECIAL_CHARS);
    
            $pattern = "/^07\d{8}$/";
    
            if (!preg_match($pattern, $number)) {
                echo '<script>show_toast("invalid phone number format! Try 07xxxxxxxx" ,"error");</script>';
            }else {
                $select_user = $conn->prepare("SELECT * FROM users WHERE phone_number = ?");
                $select_user->execute([$number]);
    
                if($select_user->rowCount() > 0){
                    echo '<script>show_toast("this number is already registered" ,"error");</script>';
                } else {
                    if($pass != $cpass){
                        echo '<script>show_toast("password doesn\'t match" ,"error");</script>';
                    } else {
                        $hashed_password = password_hash($cpass, PASSWORD_DEFAULT);
                        $insert_user = $conn->prepare("INSERT INTO users(name, phone_number, password) VALUES(?,?,?)");
                        $insert_user->execute([$name, $number, $hashed_password]);
                        
                        $confirm_user = $conn->prepare("SELECT * FROM users WHERE phone_number = ?");
                        $confirm_user->execute([$number]);
                        $fetch_user = $confirm_user->fetch();
                        $db_password = $fetch_user['password'];
    
                        if(password_verify($cpass, $db_password)){
                            $_SESSION["id"] = $fetch_user["id"];
                            header('location: index.php');
                        }
                    }
                }
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
    <title>Register</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php') ?>

    <div class="form-container"> 

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h3>Register</h3>
            <input type="text" name="name" required placeholder="Enter your name"
            class="box" maxlength="50">
            <input type="text" name="number" required placeholder="Enter your phone number" class="box" maxlength="15">
            <input type="password" minlength="4" name="password" required placeholder="Enter your password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" minlength="4" name="confirm_password" required placeholder="Confirm your password"
            class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" name="register" value="Register" class="btn">    
            <p>Already have an account? <a href="login.php">login</a></p>
        </form>

    </div>

    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>