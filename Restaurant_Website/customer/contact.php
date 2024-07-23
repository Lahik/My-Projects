<?php 
    include("../db/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
    } 

    if(isset($_POST["send"])){

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $msg = $_POST['msg'];
        $msg = filter_var($msg, FILTER_SANITIZE_STRING);
     
        $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, number, email, message) VALUES(?,?,?,?,?)");
        $insert_message->execute([$user_id, $name, $number, $email, $msg]);
        $message[] = "Message sent successfully";
    }
     
    include("../components/add_wishlist.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>
    <div class="heading">
        <h3>Contact us</h3>
    </div>

    <section class="contact">
        <div class="row">

            <div class="image">
                <img src="../images/contact-us.jpg" alt="">
            </div>

            <form action="" method="post">
                <h3>tell us something</h3>
                <input type="text" name="name" maxlength="50" class="box" 
                placeholder="Enter your name" required>
                <input type="number" name="number" min="700000000" maxlength="9999999999" class="box" 
                placeholder="Enter your number" required onkeypress="if(this.value.length == 10) return false;">
                <input type="email" name="email" maxlength="50" class="box" 
                placeholder="Enter your email" required>
                <textarea name="msg" class="box" cols="30" rows="10" maxlength="500" placeholder="Enter your message" required></textarea>
                <input type="submit" value="send message" class="btn" name="send">
            </form>
            
        </div>
    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>

</body>
</html>
<?php include("../components/footer.php"); ?>