<?php 
    include("../db/database.php");
    session_start();
    
    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
        header("location:login.php");
    }

    if(isset($_POST['reserve'])) {
        $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_SPECIAL_CHARS);
        $time = filter_input(INPUT_POST, "time", FILTER_SANITIZE_SPECIAL_CHARS);
        $adults = filter_input(INPUT_POST, "adult", FILTER_SANITIZE_SPECIAL_CHARS);
        $children = filter_input(INPUT_POST, "children", FILTER_SANITIZE_SPECIAL_CHARS);
        $comments = filter_input(INPUT_POST, "comments", FILTER_SANITIZE_SPECIAL_CHARS);

        date_default_timezone_set('Asia/Colombo');

        $date_time_string = $date . ' ' . $time;
        $date_time = new DateTime($date_time_string);
        $formatted_date_time = $date_time->format('Y-m-d H:i:s');

        $open_time_string = '11:00 AM'; 
        $close_time_string = '10:30 PM';
        $open_time = DateTime::createFromFormat('h:i A', $open_time_string)->format('H:i');
        $close_time = DateTime::createFromFormat('h:i A', $close_time_string)->format('H:i');

        $selected_date = $date_time->format('Y-m-d');
        $selected_time = $date_time->format('H:i');

        $current_datetime = new DateTime();
        $current_date = $current_datetime->format('Y-m-d');
        $current_time = $current_datetime->format('H:i');

        if($selected_date < $current_date) {
            $message[] = "Invalid reservation date! Please select current or future date";

        }else if($selected_time < $open_time || $selected_time > $close_time) {
            $message[] = "Invalid reservation time! Our restaurant will be opened from 11am to 11pm
                        and make sure to reserve atleast 30 minute earlier before the closing time";
        }else if($selected_date == $current_date && $selected_time < $current_time) {
            $message[] = "Invalid reservation time! You have selected the time which is passed now";
        }else {
            $check_duplicate_reservation = $conn->prepare('SELECT * FROM reservation WHERE user_id = ?');
            $check_duplicate_reservation->execute([$user_id]);

            $select_user = $conn->prepare('SELECT * FROM users WHERE id = ?');
            $select_user->execute([$user_id]);
            $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
            $name = $fetch_user['name'];
            $number = $fetch_user['number'];

            if($check_duplicate_reservation->rowCount() > 0) {
                $replace_reservation = $conn->prepare('UPDATE reservation SET date_time = ?, adults = ?, children = ?, comments = ?, name = ?, number = ?, status = ? WHERE user_id = ?');
                $replace_reservation->execute([$formatted_date_time, $adults, $children, $comments, $name, $number, 'pending', $user_id]);
                $message[] = "Your reservation has been updated! We will update your reservation status soon.";
            }else {
                $insert_reservation = $conn->prepare('INSERT INTO reservation(user_id, date_time, adults, children, comments, name, number, status)
                                                      VALUES(?,?,?,?,?,?,?,?)');
                $insert_reservation->execute([$user_id, $formatted_date_time, $adults, $children, $comments, $name, $number, 'pending']);
                $message[] = "Thank you for your reservation! We've received it and will update your reservation status soon.";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<body>
    <?php include("../components/user_header.php"); ?>
    <section class="reservation-container">

        <div class="reservation-box">

            <form action="" method="post" class="box">
                <h3>online reservation</h3>
                <div class="date">
                    <label for="date">Reservation date</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="time">
                    <label for="time">Reservation time</label>
                    <input type="time" id="time" name="time" required>
                </div>
                <div class="people">
                    <label for="">Number of people</label>
                    <div class="people-input">
                        <input type="number" placeholder="Adults" onkeypress="if(this.value.length == 2) return false;" min="1" max="99" name="adult" required>
                        <input type="number" placeholder="Children" onkeypress="if(this.value.length == 2) return false;" min="1" max="99" name="children">
                    </div>
                </div>
                <div class="comments">
                    <label for="comments">Comments</label>
                    <textarea name="comments" id="comments" placeholder="*Optional" cols="30" rows="5"></textarea>
                </div>
                <button type="submit" name="reserve" class="btn">Reserve online</button>
            </form>

        </div>


        <div class="reservation-status">
            <h1 class="title">Your reservation</h1>

            <div class="box-container">
                <?php 
                    $reservation = $conn->prepare("SELECT * FROM reservation WHERE user_id = ?");
                    $reservation->execute([$user_id]);

                    if($reservation->rowCount() > 0) {
                        $fetch_reservation = $reservation->fetch(PDO::FETCH_ASSOC);
                ?>
                        <div class="box">
                            <p>Reservation No : <span><?= $fetch_reservation['id'] ?></span></p>
                            <p>Date and Time : <span><?= $fetch_reservation['date_time'] ?></span></p>
                            <p>No of people : <span>Adults(<?= $fetch_reservation['adults']?>)&nbsp;&nbsp;
                                              <?= ($fetch_reservation['children'] > 0) ? 
                                              "Children(".$fetch_reservation['children'].")" : ''; ?></span></p>
                            <p>Comments : <span><?= $fetch_reservation['comments'] ?></span></p>
                <?php 
                            if($fetch_reservation['status'] == 'pending') {
                                $paid = 'yellow';
                                $status = 'pending';
                            }else if($fetch_reservation['status'] == 'approved') {
                                $paid = 'green';
                                $status = 'approved';
                            }else if($fetch_reservation['status'] == 'rejected') {
                                $paid = 'red';
                                $status = 'rejected';
                            }
                ?>                
                            <p style="text-transform: capitalize;">Reservation status : <span style="color: <?= $paid ?>; font-weight: bold"><?= $status ?></span></p>
                        </div>
                <?php                              
                    }else {
                ?>
                        <div class="box">
                            <p style="text-align: center; color: red">You haven't placed any reservations yet!</p>
                        </div>
              <?php } ?>

            </div>
        </div>



    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>

</body>
</html>
<?php include("../components/footer.php"); ?>