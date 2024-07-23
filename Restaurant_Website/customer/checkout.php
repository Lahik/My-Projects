<?php 
    include("../db/database.php");
    session_start();
    
    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
        header("location:login.php");
    }

    $orders = array();
    $total = 0;

    if(isset($_POST['place_order'])) {
        if(empty($_POST['address'])) {
           $message[] = 'Please update the address'; 
        }else {
            foreach($_SESSION['cart'] as $pid => $qty) {
                $product_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $product_query->execute([$pid]);
            
                while($fetch_product = $product_query->fetch(PDO::FETCH_ASSOC)) {
                $sub_total = $fetch_product['price'] * $qty;
                $total += $sub_total;  
                $orders[] = array(
                    'name' => $fetch_product['name'],
                    'quantity' => $qty );
                }
            }

            $order_details = '';
            foreach ($orders as $item) {
                $order_details .= $item['name'] . ' x (' . $item['quantity'] . '), ';
            }


            $order_details = rtrim($order_details, ', ');
            $name = $_POST['name'];
            $number = $_POST['number'];
            $address = $_POST['address'];
            $payment_method = $_POST['payment_method'];
            $paid = false;

            if($payment_method == 'card') {
                header('location: card_payment.php');
                $_SESSION['order_details'] = array(
                    'name' => $name,
                    'number' => $number,
                    'address' => $address,
                    'orders' => $order_details,
                    'total' => $total,
                    'payment_method' => $payment_method
                );
                exit();
            }else if($payment_method == 'cash on delivery' || $payment_method == 'take away') {
                $order_db = $conn->prepare("INSERT INTO orders(user_id, name, number, address, orders, total, payment_method, payment_status)
                                            VALUES (?,?,?,?,?,?,?,?)");
                $order_db->execute([$user_id, $name, $number, $address, $order_details, $total, $payment_method, $paid]);
                $_SESSION['cart'] = array();
                header("location: order.php");
                exit();
            }
        }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>    
    <section class="checkout">

        <h1 class="title">order summary</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="cart-items">
                <h3>cart items</h3>
                <?php 
                    foreach($_SESSION['cart'] as $pid => $qty) {
                        $product_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
                        $product_query->execute([$pid]);
                    
                        while($fetch_product = $product_query->fetch(PDO::FETCH_ASSOC)) {
                        $sub_total = $fetch_product['price'] * $qty;
                        $total += $sub_total;  
                ?>
                        <p><span class="name"><?= $fetch_product['name'];?><small>&nbsp; x <?= $qty ?></small></span><span class="price">Rs <?= $sub_total; ?></span></p>
                <?php
                        }
                    }
                ?>
                <p class="grand-total"><span class="name">Total amount: </span><span class="price"><?= $total; ?></span></p>
                <div class="view-cart-btn">
                    <a href="cart.php" class="btn">view cart</a>
                </div>
            </div>

            <div class="user-info">
                <h3>your info</h3>
                <?php 
                    $user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
                    $user_query->execute([$user_id]);
                    $fetch_user = $user_query->fetch(PDO::FETCH_ASSOC);
                ?>
                <input type="hidden" name="name" value="<?= $fetch_user['name'] ?>">
                <input type="hidden" name="number" value="<?= $fetch_user['number'] ?>">
                <input type="hidden" name="address" value="<?= $fetch_user['address'] ?>">

                <p><i class="fas fa-user"></i><span><?= $fetch_user['name'] ?></span></p>
                <p><i class="fas fa-phone"></i><span><?= $fetch_user['number'] ?></span></p>
                <p><i class="fas fa-envelope"></i><span><?= $fetch_user['email'] ?></span></p>
                <a href="update_profile.php" class="btn">update info</a>
                <h3>delivery address</h3>
                <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_user["address"] == ''){echo "please update your address";}else{ echo $fetch_user["address"];} ?></span></p>
                <a href="update_address.php" class="btn">update address</a>

                <select name="payment_method" class="box" required>
                    <option value="" disabled selected>select a payment method--</option>
                    <option value="cash on delivery">cash on delivery</option>
                    <option value="take away">Take away</option>
                    <option value="card">credit/debit card</option>
                </select>
                <input type="submit" value="place order" name="place_order" class="btn">
            </div>

        </form>
        
    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>
    
</body>
</html>
<?php include("../components/footer.php"); ?>