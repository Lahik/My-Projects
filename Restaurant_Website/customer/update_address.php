<?php 
    include("../db/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
        header('location: home.php');
    } 

    if(isset($_POST["address"])) {
        $address = $_POST["street"].', '.$_POST["area"].', '.
                   $_POST["city"].', '.$_POST["district"].', '.
                   $_POST["province"].' - '.$_POST["postal"];
        $address = filter_var($address, FILTER_SANITIZE_STRING);
     
        $update_address = $conn->prepare("UPDATE users SET address = ? WHERE id = ?");
        $update_address->execute([$address, $user_id]);

        $message[] = "Address updated successfully!";
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update address</title>
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>    
    <section class="form-container" id="address">

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <h3>update address</h3>
            <input type="text" name="street" required placeholder="Street"
            class="box" maxlength="50">
            <input type="text" name="area" required placeholder="Area"
            class="box" maxlength="50">
            <input type="text" name="city" required placeholder="City"
            class="box" maxlength="50">
            <input type="text" name="district" required placeholder="District"
            class="box" maxlength="50">
            <input type="text" name="province" required placeholder="Province"
            class="box" maxlength="50">
            <input type="number" name="postal" required placeholder="Postal code"
            class="box" onkeydown="if(this.value.length==6) return false;" min="10000" max="99999">
            <input type="submit" name="address" value="update address" class="btn">    
        </form>
    
    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>

</body>
</html>
<?php include("../components/footer.php"); ?>