<?php
    include("Components/toast.php");
    include("Database/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else if(isset($_COOKIE['remember_user'])) {
        $user_id = $_COOKIE['remember_user'];
    }else {
        $user_id = '';
        header('location: login.php');
    }

    $orders = array();
    $total = 0;

    if(isset($_POST['place_order'])) {
        if(empty($_POST['address'])) {
            echo '<script>show_toast("please update the address" ,"invalid");</script>';
        }else {
            try{
            foreach($_SESSION['cart'] as $pid => $qty) {
                $product_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $product_query->execute([$pid]);
            
                while($fetch_product = $product_query->fetch()) {
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
            $address = $_POST['address'];
            $payment_method = $_POST['payment_method'];
            $paid = false;

            if($payment_method == 'card') {
                unset($_SESSION['subscription']);
                $_SESSION['order_details'] = array(
                    'orders' => $order_details,
                    'total' => $total
                );
                header('location: payment.php');
                exit();
            }else if($payment_method == 'cash on delivery') {
                $order_db = $conn->prepare("INSERT INTO orders(user_id, address, orders, total, payment_method, payment_status)
                                            VALUES (?,?,?,?,?,?)");
                $order_db->execute([$user_id, $address, $order_details, $total, $payment_method, $paid]);
                $_SESSION['cart'] = array();
                header("location: orders.php");
                exit();
            }
            }catch (PDOException $e) {
                echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

    <section class="checkout">

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="cart-items">
                <h3>cart items</h3>
                <?php 
                    foreach($_SESSION['cart'] as $pid => $qty) {
                        $product_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
                        $product_query->execute([$pid]);
                    
                        while($fetch_product = $product_query->fetch()) {
                        $sub_total = $fetch_product['price'] * $qty;
                        $total += $sub_total;  
                ?>
                        <p><span class="name"><?= $fetch_product['name'];?><small>&nbsp; x <?= $qty ?></small></span><span class="price">Rs <?= $sub_total; ?></span></p>
                <?php
                        }
                    }
                ?>
                <p class="grand-total"><span class="name">Total amount: </span><span class="price">Rs <?= $total; ?></span></p>
                <div class="view-cart-btn">
                    <a href="cart.php" class="btn">view cart</a>
                </div>
            </div>

            <div class="user-info">
                <h3>your info</h3>
                <?php 
                    $user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
                    $user_query->execute([$user_id]);
                    $fetch_user = $user_query->fetch();
                ?>
                <input type="hidden" name="name" value="<?= $fetch_user['name'] ?>">
                <input type="hidden" name="number" value="<?= $fetch_user['phone_number'] ?>">
                <input type="hidden" name="address" value="<?= $fetch_user['address'] ?>">

                <p><i class="fas fa-user"></i><span><?= $fetch_user['name'] ?></span></p>
                <p><i class="fas fa-phone"></i><span><?= $fetch_user['phone_number'] ?></span></p>
                <a href="update_profile.php" class="btn">update info</a>
                <h3>delivery address</h3>
                <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_user["address"] == ''){echo "please update your address";}else{ echo $fetch_user["address"];} ?></span></p>
                <a href="update_address.php" class="btn">update address</a>

                <select name="payment_method" class="box" required>
                    <option value="" disabled selected>select a payment method--</option>
                    <option value="cash on delivery">cash on delivery</option>
                    <option value="card">credit/debit card</option>
                </select>
                <input type="submit" value="place order" name="place_order" class="btn">
            </div>

        </form>
        
    </section>    

    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>