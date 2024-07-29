<?php 
    include("Components/toast.php");
    include("Database/database.php");
    include("Components/card_validation.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    } else if(isset($_COOKIE['remember_user'])) {
        $user_id = $_COOKIE['remember_user'];
    } else {
        $user_id = '';
        header('location: login.php');
    }

    if(!((isset($_GET['type']) && isset($_GET['total'])) || (isset($_SESSION['subscription'])) || isset($_SESSION['order_details']))) {
        header('location: index.php');
    }

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    } else if(isset($_COOKIE['remember_user'])) {
        $user_id = $_COOKIE['remember_user'];
    } else {
        $user_id = '';
        header('location: login.php');
    }

    if(isset($_GET['type']) && isset($_GET['total'])) {
        $_SESSION['subscription'] = array(
            'type' => $_GET['type'],
            'total' => $_GET['total']
        );
        $type = $_GET['type'];
        $total = $_GET['total'];
        $payment_type = 'subscription';
    }else if(isset($_SESSION['subscription'])) {
        $type = $_SESSION['subscription']['type'];
        $total = $_SESSION['subscription']['total'];
        $payment_type = 'subscription';
    }else if(isset($_SESSION['order_details'])) {
        $type = "item/items";
        $total = $_SESSION['order_details']['total'];
        $payment_type = 'product';
    }

    try{
    if(isset($_POST['pay'])) {
        $validation = true;
        if($payment_type === 'subscription') {
            $plan_type = str_replace('Subscription-', '', $type);
            if(!validateTypeAndTotal($plan_type, $total, $conn)) {
                $validation = false;
            }
        }

        $cardHolder = $_POST['card-holder'];
        $cardNumber = $_POST['card-number'];
        $expiryDate = $_POST['exp'];
        $cvc = $_POST['cvc'];

        if(!$validation) {
            echo '<script>show_toast("an error occured while making the payment" ,"error");</script>';
        }
        else if(!validateCardNumber($cardNumber)) {
            echo '<script>show_toast("invalid card number" ,"error");</script>';
        }
        else if(!validateExpiryDate($expiryDate)) {
            echo '<script>show_toast("your card has expired" ,"error");</script>';
        }
        else {
            if($payment_type === 'product') {
                $select_member = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $select_member->execute([$user_id]);
                $fetch_member = $select_member->fetch();

                $order_db = $conn->prepare("INSERT INTO orders(user_id, address, orders, total, payment_method, payment_status)
                                            VALUES (?,?,?,?,?,?)");
                $order_db->execute([$user_id, $fetch_member['address'], $_SESSION['order_details']['orders'], $total, 'credit/debit card', true]);
                $_SESSION['cart'] = array();
                unset($_SESSION['order_details']);
                header("location: orders.php");
            }else if($payment_type === 'subscription') {
                $check_subscription = $conn->prepare("SELECT * FROM membership WHERE user_id = ? AND end >= CURDATE() ORDER BY end DESC");
                $check_subscription->execute([$user_id]);
                $existing_subscription = $check_subscription->fetch();

                if ($existing_subscription) {
                    $old_sub_end_date = $existing_subscription['end'];
                    $start_date = date("Y-m-d", strtotime($old_sub_end_date . '+1 day'));
                    $end_date = date("Y-m-d", strtotime($start_date . '+30 days'));
                } else {
                    $start_date = date("Y-m-d");
                    $end_date = date("Y-m-d", strtotime('+30 days'));
                }

                $cleaned_type = str_replace('Subscription-', '', $type);
                
                $add_membership = $conn->prepare("INSERT INTO membership(user_id, plan, start, end) VALUES (?,?,?,?)");
                $add_membership->execute([$user_id, $cleaned_type, $start_date, $end_date]);
                unset($_SESSION['subscription']);
                header('location: membership.php');
            }
        }
    }

    }catch (PDOException $e) {
        echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

    <div class="wrapper">
        <div class="total-container">
            <p class="type"><?= $type ?> <span>/mo</span></p>
            <p class="total"><span>Rs </span> <?= $total ?></p>
        </div>

        <div class="payment">
            <h2>Payment Gateway</h2>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="card space icon-relative">
                    <label class="label">Card holder:</label>
                    <input type="text" class="input" required name="card-holder" placeholder="Cardholder Name">
                    <i class="fas fa-user"></i>
                </div>
                <div class="card space icon-relative">
                    <label class="label">Card number:</label>
                    <input type="text" class="input" required name="card-number" data-mask="0000 0000 0000 0000" placeholder="Card Number">
                    <i class="far fa-credit-card"></i>
                </div>
                <div class="card-grp space">
                    <div class="card-item icon-relative">
                        <label class="label">Expiry date:</label>
                        <input type="text" required name="exp" class="input" data-mask="00 / 00" placeholder="00 / 00">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <div class="card-item icon-relative">
                        <label class="label">CVC:</label>
                        <input type="text" class="input" required name="cvc" data-mask="000" placeholder="000">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                <button type="submit" name="pay" class="pay-btn">pay</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>