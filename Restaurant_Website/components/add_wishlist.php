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
            
            $check_wishlist_numbers = $conn->prepare("SELECT * FROM wishlist WHERE 
            user_id = ? AND food_id = ?");
            $check_wishlist_numbers->execute([$user_id, $pid]);
            
            if($check_wishlist_numbers->rowCount() > 0) {
                $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND food_id = ?");
                $delete_wishlist->execute([$user_id, $pid]);
                $message[] = 'Removed from wishlist';
            }else {
                $insert_wishlist = $conn->prepare("INSERT INTO wishlist(user_id,food_id) VALUES(?,?)");
                $insert_wishlist->execute([$user_id, $pid]);
                $message[] = 'Added to wishlist';
            }
        }
    }
?>