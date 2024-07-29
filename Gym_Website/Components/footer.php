<?php
    if(isset($_POST['submit'])) {
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);

        try{
        $check_newsletter = $conn->prepare('SELECT * FROM newsletter WHERE email = ?');
        $check_newsletter->execute([$email]);

        if($check_newsletter->rowCount() > 0) {
            $msg = 'Email already registered to newsletter!';
        }else {
            $newsletter = $conn->prepare("INSERT INTO newsletter(name, email) VALUES(?,?)");
            $newsletter->execute([$name, $email]);
            $msg = 'Thanks for subscribing to our newsletter';
        }
        }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
        }
    }
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<footer class="footer">
    <section class="grid">

        <div class="links">
            <div class="logo">
                <!-- <img src="images/logo.png" alt=""> -->
                <p>champions<span>gym</span></p>
            </div>
            <p id="des">At our gym, we're dedicated to helping people improve their overall well-being through tailored workouts, creative fitness programs, and consistent encouragement</p>
            <a href="mailto:championsgymak@gmail.com"><i class="fas fa-envelope"></i>championsgymak@gmail.com</a>
            <a href="tel:+94774491819"><i class="fas fa-phone"></i>(+94)77 449 1819</a>
            <p id="time"><i class="fas fa-clock"></i>6am - 10pm</p>
            <a target="_blank" href="https://www.google.com/maps/place/Champions+GYM+Akurana/@7.3596718,80.615282,17z/data=!3m1!4b1!4m6!3m5!1s0x3ae342c5e1fe76ff:0xc0398d2ec151dd46!8m2!3d7.3596718!4d80.6178569!16s%2Fg%2F11bw2_bqp0?entry=ttu"><i class="fas fa-location-dot"></i>162/2/2, Mathale Rd, Kudugala, Akurana, Kandy</a>
        </div>

        <div class="useful-links">
            <h3>useful links</h3>
            <a href="index.php">home</a>
            <a href="index.php#about">about</a>
            <a href="index.php#pricing">pricing</a>
            <a href="membership.php">membership</a>
            <a href="shop.php">shop</a>
            <a href="orders.php">orders</a>
        </div>

        <div class="our-services">
            <h3>our services</h3>
            <p>Personal training sessions</p>
            <p>Nutritional counselling</p>
            <p>Cardio & Strengh training</p>
            <p>equipment access</p>
            <p>access to treadmill</p>
        </div>

        <div class="news-letter">
            <h3>our newsletter</h3>
            <p>subscribe to our news letter...</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="text" name="name" maxlength="50" placeholder="Name" required>
                <input type="email" name="email" maxlength="50" placeholder="Email" required>
                <button type="submit" name="submit" class="btn">submit</button>
            </form>
            <p style="text-align: center; border-left: none; color:#fff"><?= isset($msg) ? $msg : '' ?></p>
        </div>

    </section>
    <div class="social-media">
        <h3>follow us</h3>
        <div class="social">
            <a target="_blank" href="https://web.facebook.com/Championsgym.akurana/?_rdc=1&_rdr"><i class="fa-brands fa-facebook"></i></a>
            <a target="_blank" href="https://www.instagram.com/explore/locations/304460537167792/champions-gym/"><i class="fa-brands fa-instagram"></i></a>
            <a target="_blank" href="https://wa.me/+94774491819"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
    </div>
    <div class="credit">
        &copy 2024 <span>Champions Gym</span>. All Rights Reserved.
        <p class="developer">created and developed by <span>lahik ahamed</span></p>
    </div>
</footer>