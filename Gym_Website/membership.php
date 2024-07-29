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
    <title>Membership</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>

    <section class="table-container">
    <?php 
    try{
    $select_membership = $conn->prepare("SELECT * FROM membership WHERE user_id = ?");
    $select_membership->execute([$user_id]);
    if($select_membership->rowCount() > 0) {
    ?>
        <div class="table">
            <div class="table-header">
                <h1>Your Membership Details</h1>
            </div>
            <div class="table-body">
                <table>
                    <thead>
                        <tr>
                            <th>Subscription plan</th>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        while($fetch_membership = $select_membership->fetch()) {
                            $logo = ($fetch_membership['plan'] == 'basic') ? 'check-circle' : (($fetch_membership['plan'] == 'standard') ? 'star' : 'crown');
                    ?>
                            <tr>
                                <td>
                                    <p class="plan <?= $fetch_membership['plan'] ?>"><?= $fetch_membership['plan'] ?> <i class="fas fa-<?= $logo ?>"></i></p>
                                </td>
                                <td><?= $fetch_membership['start'] ?></td>
                                <td><?= $fetch_membership['end'] ?></td>
                            </tr>
                    <?php 
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }else {
    ?>
        <div class="membership-empty">
            <p>You haven't joined to any membership plans yet</p>
            <div class="join">
                <a href="index.php#pricing" class="btn">Join now</a>
            </div>
        </div>
    <?php     
    }
    }catch (PDOException $e) {
        echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
    }
    ?>
    </section>
        
    <?php include("components/footer.php"); ?>
    <script src="JS/script.js"></script>
</body>
</html>