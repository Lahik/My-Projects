<?php 
    include("../db/database.php");
    session_start();
    
    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
        header("location:login.php");
    }

    if(isset($_POST['pay'])) {

        $name = $_SESSION['order_details']['name'];
        $number = $_SESSION['order_details']['number'];
        $address = $_SESSION['order_details']['address'];
        $orders = $_SESSION['order_details']['orders'];
        $total = $_SESSION['order_details']['total'];
        $payment_method = $_SESSION['order_details']['payment_method'];
        $paid = true;

        $order_db = $conn->prepare("INSERT INTO orders(user_id, name, number, address, orders, total, payment_method, payment_status)
                                    VALUES (?,?,?,?,?,?,?,?)");
        $order_db->execute([$user_id, $name, $number, $address, $orders, $total, $payment_method, $paid]);
        $_SESSION['cart'] = array();
        header("location: order.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>
    <section class="payment-container">

            <div class="payment-box">
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="payment">
                    <h3>card payment</h3>

                    <div class="card-name">
                        <input type="text" name="card_name" placeholder="ENTER CARDHOLDER'S NAME" required>
                        <i class="fas fa-credit-card"></i>
                    </div>

                    <div class="card-number">
                        <input type="number" id="card_number" name="card_number" maxlength="12" minlength="12" placeholder="ENTER CARD NUMBER" required>
                    </div>

                    <div class="date_cvv">
                        <div class="date">
                            <input type="text"  maxlength="5" minlength="5" name="exp_date" placeholder="MM/YY" required>
                        </div>
                        <div class="cvv">
                            <input type="number" minlength="3" maxlength="3" placeholder="CVV" name="cvv" required>
                        </div>
                    </div>
                    <button type="submit" name="pay" class="btn">pay now</button>
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