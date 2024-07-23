<?php 
    
    if(($user_id) == '') {
        header('location: login.php');
    }else {
        $user_id = $_SESSION["id"];
        $product_id = $_POST['delete'];

        $delete_query = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND food_id = ?");
        $delete_query->execute([$user_id, $product_id]);

        if($delete_query->rowCount() > 0) {
            $message[] = "Product removed successfully!";
        } else {
            $message[] = "Failed to remove product!";
        }
    }

    if(isset($_POST['delete'])) {
        $user_id = $_SESSION["id"];

        $delete_query = $conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
        $delete_query->execute([$user_id]);

        if($delete_query->rowCount() > 0) {
            $message[] = "All products removed successfully!";
        } else {
            $message[] = "Failed to remove!";
        }
    }
?>