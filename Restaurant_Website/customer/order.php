<?php 
    include("../db/database.php");
    session_start();
    
    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    } else {
        $user_id = '';
        header("location:login.php");
    } 
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>
    <section class="orders">

        <h1 class="title">your orders</h1>

        <div class="box-container">
            <?php 
                $orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
                $orders->execute([$user_id]);

                if($orders->rowCount() > 0) {
                    while($fetch_orders = $orders->fetch(PDO::FETCH_ASSOC)) {
            ?>
                        <div class="box">
                            <p>Placed on : <span><?= $fetch_orders['order_date_time'] ?></span></p>
                            <p>Name : <span><?= $fetch_orders['name'] ?></span></p>
                            <p>Number : <span><?= $fetch_orders['number'] ?></span></p>
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
                    <div class="box">
                        <p style="text-align: center; color: red">You haven't placed any orders yet!</p>
                    </div>
          <?php } ?>

        </div>
        
    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>

</body>
</html>
<?php include("../components/footer.php"); ?>