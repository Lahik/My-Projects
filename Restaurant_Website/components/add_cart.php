<?php 
    if(isset($_POST['add_to_cart'])) {

        if($user_id == '') {
            header('location: login.php');
        }else {
            $pid = $_POST['pid'];
            $qty = $_POST['qty'];

            if(isset($_SESSION['cart'][$pid])) {
                $_SESSION['cart'][$pid] += $qty;
            }else {
                $_SESSION['cart'][$pid] = $qty;
            }
            $message[] = 'Added to cart';
        }

        
    }
?>