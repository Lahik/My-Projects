<?php
    if(isset($_POST['submit'])) {
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        
        $check_newsletter = $conn->prepare('SELECT * FROM newsletter WHERE email = ?');
        $check_newsletter->execute([$email]);

        if($check_newsletter->rowCount() > 0) {
            $msg = 'Email already registered to newsletter!';
        }else {
            $newsletter = $conn->prepare("INSERT INTO newsletter(name, email) VALUES(?,?)");
            $newsletter->execute([$name, $email]);
            $msg = 'Thanks for subscribing to our newsletter';
        }
        
    }
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<link rel="stylesheet" href="../CSS/customer_style.css">
<footer class="footer">
    <section class="grid">

        <div class="links">
            <div class="logo">
                <img src="../images/logo.png" alt="">
            </div>
            <p id="des">To be the premier culinary destination where the art of flavors converges with warm hospitality, creating memorable experiences that linger on the palates and hearts of our patrons</p>
            <a href="mailto:theoutercloverestaurant@gmail.com"><i class="fas fa-envelope"></i>theoutercloverestaurant@gmail.com</a>
            <a href="tel:2503541667"><i class="fas fa-phone"></i>(250)354-1667</a>
            <p id="time"><i class="fas fa-clock"></i>11am - 11pm</p>
            <a href="https://www.google.com/maps/dir//536+Stanley+St,+Nelson,+BC+V1L+1N1,+Canada/@49.4910198,-117.3777511,12z/data=!4m8!4m7!1m0!1m5!1m1!1s0x537cb6a67c259213:0x4737156aa85a2ed5!2m2!1d-117.2953507!2d49.4910491?entry=ttu"><i class="fas fa-location-dot"></i>536 Stanley st, nelson, BC v1l 1n2, CA</a>
        </div>

        <div class="useful-links">
            <h3>useful links</h3>
            <a href="home.php">home</a>
            <a href="menu.php">menu</a>
            <a href="order.php">orders</a>
            <a href="reservation.php">Reservation</a>
            <a href="about.php">about</a>
            <a href="contact.php">contact</a>
        </div>

        <div class="our-services">
            <h3>our services</h3>
            <p>online ordering</p>
            <p>take away</p>
            <p>free home delivery</p>
            <p>dine in experience</p>
            <p>special promotions</p>
            <p>catering services</p>
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
            <a href="https://www.facebook.com/outerclove/"><i class="fa-brands fa-facebook" style="color: #6d6d6d;"></i></a>
            <a href="https://www.instagram.com/outerclove/"><i class="fa-brands fa-instagram" style="color: #6d6d6d;"></i></a>
            <a href="https://www.twitter.com/outerclove/"><i class="fa-brands fa-twitter" style="color: #6d6d6d;"></i></a>
            <a href="https://www.whatsapp.com/outerclove/"><i class="fa-brands fa-whatsapp" style="color: #6d6d6d;"></i></a>
        </div>
    </div>
    <div class="credit">Created and Designed by <span>Mr. Lahik Ahamed</span> | &copy all rights 
    reserved!</div>
</footer>