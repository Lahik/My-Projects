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

    if(isset($_POST["address"])) {
        $address = $_POST["street"].', '.$_POST["area"].', '.
                   $_POST["city"].', '.$_POST["district"].', '.
                   $_POST["province"].' - '.$_POST["postal"];
        $address = filter_var($address, FILTER_SANITIZE_STRING);
    try{ 
        $update_address = $conn->prepare("UPDATE users SET address = ? WHERE id = ?");
        $update_address->execute([$address, $user_id]);

        header('location: checkout.php');
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
    <title>Update address</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

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

    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>