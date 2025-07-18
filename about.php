
<?php 
    session_start();
    include 'components/connection.php';

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id =' ';
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header("location: login.php");
    }
?>


<style type="text/css">
    <?php include 'css/style.css'; ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawasome/css/all.css">
    <title>Gesge Restaurant - about us Page</title>
</head>
<body>

    <?php include 'components/header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>about us</h1>
        </div>
        <div class="title2">
            <a href="index.php">home</a><span> / about </span>
        </div>

        <section class="services">
            <div class="title">
                <h1>why choose us</h1>
                <img src="images/separator.svg" class="separator" alt="separator">
                <p>we offer delicious dishes with fresh ingredients, served in a warn and welcoming atmosphere. our passionate chefs, exceptional service, and blend of traditon with modern flavors make every visit special.</p>
            </div>
            <div class="box-container">
                <div class="box">
                    <img src="images/services (5).png" alt="serivices">
                    <div class="detail">
                        <h3>great savings</h3>
                        <p>save big every order</p>
                    </div>
 
                </div>
                <div class="box">
                    <img src="images/services (2).png" alt="serivices">
                    <div class="detail">
                        <h3>24*7 support</h3>
                        <p>one-on-one support</p>
                    </div>
 
                </div>
                <div class="box">
                    <img src="images/services (7).png" alt="serivices">
                    <div class="detail">
                        <h3>gift vouchers</h3>
                        <p>vouchers on every festivals</p>
                    </div>
 
                </div>
                <div class="box">
                    <img src="images/services.png" alt="serivices">
                    <div class="detail">
                        <h3>worldwide delivery</h3>
                        <p>dropship worldwide</p>
                    </div>
 
                </div>
            </div>
        </section>

        <div class="about">
            <div class="row">
                <div class="img-box">
                    <img src="images/inner.png" alt="">
                </div>
                <div class="detail">
                    <h1>visit our beautiful showroom!</h1>
                    <img src="images/separator.svg" class="separator" alt="">
                    <p>come see where the magic happens! our beautifully designed space is the perfect place to enjoy good food, good company, and a great atmosphere. whether you're planning a casual meal or a special celebration, we'd love to welcome you.</p>
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
            </div>
        </div>

        <div class="testimonial-container">
            <div class="title">
                <h1>what people say about us</h1>
                <img src="images/separator.svg" class="separator" alt="">
            </div>

            <div class="container">
                <div class="testimonial-item active">
                    <img src="images/profileImg1.jpg" alt="">
                    <h1>Alex Davidson</h1>
                    <p>the food was absolutely amazing! Every dish was full of flavor, and the staff made us feel right at home. can't wait to come back!</p>
                </div>
                <div class="testimonial-item">
                    <img src="images/profileImg2.jpg" alt="">
                    <h1>Sarah Lanwight</h1>
                    <p>Hands down are of the coziest spota in town. Great vibes, friendly people, and the portions? Generous and totally worth it.</p>
                </div>
                <div class="testimonial-item">
                    <img src="images/profileImg3.jpg" alt="">
                    <h1>James Town</h1>
                    <p>Loved the blend of traditional and modern flavors. it's clear they put a lot of heart into what they do. Highly recommended!</p>
                </div>
                <div class="testimonial-item">
                    <img src="images/testimonial (2).jpg" alt="">
                    <h1>Kevin Moon</h1>
                    <p>The place is beautiful, the food is fresh, and the service is top-notch. Perfect spot for a date night or family dinner.</p>
                </div>
           
                <div class="testimonial-item">
                    <img src="images/testimonial (3).jpg" alt="">
                    <h1>Lina Mubin </h1>
                    <p>We stumbled upon this place by accident - and now it's our go-to! Super welcoming and the dishes are always on point.</p>
                </div>
           
                <div class="testimonial-item">
                    <img src="images/testimonial (4).jpg" alt="">
                    <h1>Greecs Dalwin</h1>
                    <p>From the moment we walked in, everything felt just right, Delicious meals, Relaxed atmosphere, and such lovely staff.</p>
                </div>
                <div class="left-arrow" id="prevSlide"><i class="fas fa-chevron-left"></i></div>
                <div class="right-arrow" id="nextSlide"><i class="fas fa-chevron-right"></i></div>
            </div>


        </div>
        
        <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>


    <script src="script.js"></script>
    <?php include 'components/alert.php'; ?>
   
</body>


</html>