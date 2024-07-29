<?php 
    if(isset($_POST["add_to_wishlist"])) {

        if(($user_id) == '') {
            header('location: login.php');
        }else {
            $pid = $_POST['pid'];
            $pid = filter_var($pid, FILTER_SANITIZE_STRING);
            $name = $_POST['name'];
            $name = filter_var($name, FILTER_SANITIZE_STRING);
            $price = $_POST['price'];
            $price = filter_var($price, FILTER_SANITIZE_STRING);
            $image = $_POST['image'];
            $image = filter_var($image, FILTER_SANITIZE_STRING);
            $qty = $_POST['qty'];
            $qty = filter_var($qty, FILTER_SANITIZE_STRING);
            
            try{
            $check_wishlist_numbers = $conn->prepare("SELECT * FROM wishlist WHERE 
            user_id = ? AND item_id = ?");
            $check_wishlist_numbers->execute([$user_id, $pid]);
            
            if($check_wishlist_numbers->rowCount() > 0) {
                $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND item_id = ?");
                $delete_wishlist->execute([$user_id, $pid]);
                echo '<script>show_toast("item removed from wishlist" ,"success");</script>';
            }else {
                $insert_wishlist = $conn->prepare("INSERT INTO wishlist(user_id,item_id) VALUES(?,?)");
                $insert_wishlist->execute([$user_id, $pid]);
                echo '<script>show_toast("item added to wishlist" ,"success");</script>';
            }
            }catch (PDOException $e) {
                echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
            }
        }
    }

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
            echo '<script>show_toast("added to cart" ,"success");</script>';
        }
    }
?>