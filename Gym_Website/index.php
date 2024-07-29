<?php 
    session_start();
    include("Components/toast.php");
    include("Database/database.php");
    include("Components/bmi_calculator.php");

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    } else if(isset($_COOKIE['remember_user'])) {
        $user_id = $_COOKIE['remember_user'];
    } else {
        $user_id = '';
    }

    if(isset($_POST["send"])){

        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_SPECIAL_CHARS);
     
        $insert_message = $conn->prepare("INSERT INTO messages (user_id, name, message) VALUES(?,?,?)");
        $insert_message->execute([$user_id, $name, $message]);
        echo '<script>show_toast("message sent successfully!" ,"success");</script>';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"/>
    <link rel="icon" type="image/png" href="images/favicon.png"/>
</head>
<body>
    <?php include('Components/header.php'); ?>
    
    <div class="home">
        
        <div class="swiper home-slider">
            <div class="swiper-wrapper">

                <div class="swiper-slide slide" style="background: url(images/home-bg-1.jpg);">
                    <div class="content">
                        <span>be strong, be fit</span>
                        <h3>Make yourself stronger than excuses</h3>
                        <a href="#pricing" class="btn">get started <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="swiper-slide slide" style="background: url(images/home-bg-2.jpg);">
                    <div class="content">
                        <span>be strong, be fit</span>
                        <h3>Make yourself stronger than excuses</h3>
                        <a href="#pricing" class="btn">get started <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="swiper-slide slide" style="background: url(images/home-bg-3.jpg);">
                    <div class="content">
                        <span>be strong, be fit</span>
                        <h3>Make yourself stronger than excuses</h3>
                        <a href="#pricing" class="btn">get started <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

            </div>
            <div class="swiper-pagination"></div>
        </div>

    </div>

    <section class="about-container" id="about">
        <div class="about-image">
            <img class="dot-bg" src="images/dot-bg.png"/>
            <img src="images/about.png"/>
        </div>
        <div class="about-content">
            <h2 class="section-header">Our Story</h2>
            <p class="section-description">
            Led by our team of expert and motivational instructors, "The Class You
            Will Get Here" is a high-energy, results-driven session that combines
            a perfect blend of cardio, strength training, and functional
            exercises.
            </p>
            <div class="about-grid">
                <div class="about-card">
                    <span><i class="ri-open-arm-line"></i></span>
                    <div>
                        <h4>Open Door Policy</h4>
                        <p>
                        We believe in providing unrestricted access to all individuals,
                        regardless of their fitness level, age, or background.
                        </p>
                    </div>
                </div>
                <div class="about-card">
                    <span><i class="ri-shield-cross-line"></i></span>
                    <div>
                        <h4>Fully Insured</h4>
                        <p>
                        Your peace of mind is our top priority, and our commitment to
                        your safety extends to every aspect of your fitness journey.
                        </p>
                    </div>
                </div>
                <div class="about-card">
                    <span><i class="ri-p2p-line"></i></span>
                    <div>
                        <h4>Personal Trainer</h4>
                        <p>
                        With personalized workout plans tailored to your needs, we will
                        ensure that you get the most out of your gym experience.
                        </p>
                    </div>  
                </div>
            </div>
        </div>
    </section>

    <section class="pricing" id="pricing">
        <div class="information">
            <span>Pricing plan</span>
            <h3>Affordable pricing plan for yours</h3>
            <p>Explore our gym membership plans: Basic, Standard and Premium. Find the perfect fit for your fitness
            journey with flexible options designed to meet your needs and goals.</p>
            <p><i class="fas fa-check"></i> cardio excercise</p>
            <p><i class="fas fa-check"></i> weightlifting</p>
            <p><i class="fas fa-check"></i> diet plans</p>
            <p><i class="fas fa-check"></i> overall results</p>
        </div>
        <?php 
        try{
        $basic = 0; $standard = 0; $premium = 0;
        $select_plans = $conn->prepare("SELECT * FROM membership_plan");
        $select_plans->execute();
        while($fetch_plans = $select_plans->fetch()) {
            switch($fetch_plans['plan']) {
                case 'basic':
                    $basic = $fetch_plans['amount'];
                    break;
                case 'standard':
                    $standard = $fetch_plans['amount'];
                    break;
                case 'premium':
                    $premium = $fetch_plans['amount'];
                    break;
            }
        }
        }catch (PDOException $e) {
            echo '<script>show_toast("Error: '. $e->getMessage() .'" ,"error");</script>';
        }
        ?>
        <div class="plan basic">
            <h3>Basic plan <i class="fas fa-check"></i></h3>
            <div class="price"><span>Rs</span> <?= $basic ?><span>/mo</span></div>
            <div class="list">
                <p><i class="fas fa-check-circle"></i> Access to all equipments</p>
                <p><i class="fas fa-check-circle"></i> Weightlifting</p>
                <p><i class="fas fa-check-circle"></i> Overall results</p>
                <p class="unavailable"><i class="fas fa-check-circle"></i> Access to threadmill</p>
                <p class="unavailable"><i class="fas fa-check-circle"></i> Appointing a personal trainer</p>
                <p class="unavailable"><i class="fas fa-check-circle"></i> Personalized diet plans</p>
            </div>
            <a href="payment.php?total=<?= $basic ?>&type=Subscription-basic" class="btn">get started <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="plan">
            <h3>Standard plan <i class="fas fa-star"></i></h3>
            <div class="price"><span>Rs</span> <?= $standard ?><span>/mo</span></div>
            <div class="list">
                <p><i class="fas fa-check-circle"></i> Access to all equipments</p>
                <p><i class="fas fa-check-circle"></i> Weightlifting</p>
                <p><i class="fas fa-check-circle"></i> Overall results</p>
                <p><i class="fas fa-check-circle"></i> Access to threadmill</p>
                <p class="unavailable"><i class="fas fa-check-circle"></i> Appointing a personal trainer</p>
                <p class="unavailable"><i class="fas fa-check-circle"></i> Personalized diet plans</p>
            </div>
            <a href="payment.php?total=<?= $standard ?>&type=Subscription-standard" class="btn">get started <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="plan">
            <h3>Premium plan <i class="fas fa-crown"></i></h3>
            <div class="price"><span>Rs</span> <?= $premium ?><span>/mo</span></div>
            <div class="list">
                <p><i class="fas fa-check-circle"></i> Access to all equipments</p>
                <p><i class="fas fa-check-circle"></i> Weightlifting</p>
                <p><i class="fas fa-check-circle"></i> Overall results</p>
                <p><i class="fas fa-check-circle"></i> Access to threadmill</p>
                <p><i class="fas fa-check-circle"></i> Appointing a personal trainer</p>
                <p><i class="fas fa-check-circle"></i> Personalized diet plans</p>
            </div>
            <a href="payment.php?total=<?= $premium ?>&type=Subscription-premium" class="btn">get started <i class="fas fa-arrow-right"></i></a>
        </div>
    </section>

    <section class="calculate" id="bmi">
        <div class="calculate-container">
            <div class="title-description">
                <div class="titles">
                    <h1 class="title-border">calculate</h1>
                    <h1 class="title">your bmi</h1>
                </div>
    
                <p class="description">
                    The body mass index (BMI) calculator calculates body mass index from your height and weight.
                </p>
            </div>

            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="calculate-form" method="post">
                <div class="calculate-box">
                    <input type="number" name="height" onkeypress="if(this.value.length == 3) return false;" required placeholder="Height">
                    <label for="" class="calculate-label">cm</label>
                </div>
                <div class="calculate-box">
                    <input type="number" name="weight" onkeypress="if(this.value.length == 3) return false;" required placeholder="Weight">
                    <label for="" class="calculate-label">kg</label>
                </div>

                <button type="submit" name="calculate-bmi" class="btn">
                    calculate now <i class="fas fa-arrow-right"></i>
                </button>
                
                <div class="message-container">
                <?php 
                if(isset($_SESSION['bmi'])) {
                    echo '<p class="calculate-message '. $_SESSION['bmi']['class'] .'">Your BMI score is '. $_SESSION['bmi']['bmi_score'] . ' - '. $_SESSION['bmi']['message'] .'</p>';
                }
                ?>
                </div>
            </form>
        </div>
    </section>

    <section class="contact">
        <div class="row">

            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <h3>tell us something</h3>
                <input type="text" name="name" maxlength="50" class="box" 
                placeholder="Enter your name" required>
                <textarea name="message" class="box" cols="30" rows="10" maxlength="500" placeholder="Enter your message" required></textarea>
                <input type="submit" value="send message" class="btn" name="send">
            </form>
            
        </div>
    </section>
    
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <?php include('Components/footer.php'); ?>
    
    <div class="loader">
        <img src="images/loader.jpg" alt="">
    </div>

    <script src="JS/script.js"></script>
</body>
</html>