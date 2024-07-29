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
        header('location: login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

    <section class="orders">

    <?php 
        try{
        $orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
        $orders->execute([$user_id]);

        if($orders->rowCount() > 0) {
            while($fetch_orders = $orders->fetch()) {
    ?>
                <div class="box">
                    <p>Placed on : <span><?= $fetch_orders['order_date_time'] ?></span></p>
                    <p>Address : <span><?= $fetch_orders['address'] ?></span></p>
                    <p>Your orders : <span><?= $fetch_orders['orders'] ?></span></p>
                    <p>Payment method : <span><?= $fetch_orders['payment_method'] ?></span></p>
                    <p>Total amount : <span><?= $fetch_orders['total'] ?>/-</span></p>
    <?php 
                    if($fetch_orders['payment_status'] == 1) {
                        $paid = 'green';
                        $status = 'paid';
                    }else {
                        $paid = 'red';
                        $status = 'pending';
                    }
    ?>                
                    <p>payment status : <span style="color: <?= $paid ?>; font-weight: bold"><?= $status ?></span></p>
                </div>
    <?php                              
            }
        }else {
    ?>
            <div class="empty">
                <p>You haven't placed any orders yet!</p>
                <a href="shop.php" class="btn">shop</a>
            </div>
    <?php } 
    }catch (PDOException $e) {
        echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
    }    
    ?>
    </section>

    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>