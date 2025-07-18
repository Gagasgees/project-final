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
    <title>Gesge Restaurant - Home Page</title>
</head>
<body>

    <?php include 'components/header.php'; ?>
    <div class="main">
        <section class="home-section">
            <div class="slider">
                <div class="slider_slider slide1">
                    <div class="overlay"></div>
                    <div class="slide_detail">
                        <h1>Taste the flavors of Color</h1>
                        <img src="images/separator.svg" class="separator" alt="">
                        <p>A delicious menu made for every color</p>
                        <a href="view_menu.php" class="btn">shop now</a>
                    </div>
                    <div class="hero-dc-top"></div>
                    <div class="hero-dc-bottom"></div>
                </div>

                <div class="slider_slider slide2">
                    <div class="overlay"></div>
                    <div class="slide_detail">
                        <h1>Sweet Homemade Desserts</h1>
                        <img src="images/separator.svg" class="separator" alt="">
                        <p>The perpect way to end your meal</p>
                        <a href="view_menu.php" class="btn">shop now</a>
                    </div>
                    <div class="hero-dc-top"></div>
                    <div class="hero-dc-bottom"></div>
                </div>

                <div class="slider_slider slide3">
                    <div class="overlay"></div>
                    <div class="slide_detail">
                        <h1>Fresh & Flavorful Drinks</h1>
                        <img src="images/separator.svg"  class="separator" alt="">
                        <p>Refreshing choices to brighten your day</p>
                        <a href="view_menu.php" class="btn">shop now</a>
                    </div>
                    <div class="hero-dc-top"></div>
                    <div class="hero-dc-bottom"></div>
                </div>

                <div class="slider_slider slide4">
                    <div class="overlay"></div>
                    <div class="slide_detail">
                        <h1>Shop Smart at Color Grocery</h1>
                        <img src="images/separator.svg" class="separator" alt="">
                        <P>Fresh ingredients and daily essentials in one place</P>
                        <a href="view_menu.php" class="btn">shop now</a>
                    </div>
                    <div class="hero-dc-top"></div>
                    <div class="hero-dc-bottom"></div>
                </div>

                <div class="left-arrow"><i class="fas fa-chevron-left"></i></div>
                <div class="right-arrow"><i class="fas fa-chevron-right"></i></div>
            </div>
        </section>

        <!-- SECTION THUMB -->
        <section class="thumb">
            <div class="box-container">
                <div class="box">
                    <img src="images/icon-14.png" alt="">
                    <h3>Green Tea</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="box">
                    <img src="images/icon-18.png" alt="">
                    <h3>Green Tea</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="box">
                    <img src="images/icon-15.png" alt="">
                    <h3>Green Tea</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="box">
                    <img src="images/icon-17.png" alt="">
                    <h3>Green Tea</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </section>


        <!-- DISC SECTION -->

        <section class="container">
            <div class="box-container">
                <div class="box">
                    <img src="images/product10.jpg" alt="about section">
                </div>
                <div class="box">
                    <img src="images/logo.png" class="logo-disc" alt="">
                    <span>Blueberry Cup Ice Cream</span>
                    <img src="images/separator.svg" class="separator" alt="">
                    <h1>save up to 30% off</h1>
                    <p>Blueberry ice cream have a taste sweet, smooth and have colorfull taste.</p>
                </div>
            </div>
        </section>


        <!-- SHOP SECTION -->

        <section class="shop">
            <div class="title">
                <h1>Trending Product</h1>
                <img src="images/separator.svg" class="separator" alt="">
                <p>This is of menus about trending product in our restaurant</p>
            </div>
            <div class="row">
                <img src="images/shape-5.png" class="shape1" alt="">
                <img src="images/shape-1.png" class="shape2"  alt="">
                <img src="images/shape-4.png" class="shape3"  alt="">
                <img src="images/shape-6.png" class="shape4"  alt="">
            </div>

            <div class="box-container">
                <div class="box">
                    <img src="images/food-31.png" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="images/food-35.png" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="images/2.png" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="images/3.png" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="images/desserts.png" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
                <div class="box">
                    <img src="images/food-42.png" alt="">
                    <a href="view_products.php" class="btn">shop now</a>
                </div>
            </div>
        </section>



        <!-- SHOP CATEGORY SECTION -->

        <section class="shop-category">
            <div class="box-container" id="slider-big-offers">
                <div class="slides">
                    <div class="slide active">
                        <img src="images/food-15.png" alt="category">
                        <div class="detail">
                            <span>BIG OFFERS</span>
                            <img src="images/separator.svg" class="separator" alt="">
                            <h1>Extra 15% off</h1>
                            <h3>Caesar Salad</h3>
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/food-1.png" alt="category">
                        <div class="detail">
                            <span>BIG OFFERS</span>
                            <img src="images/separator.svg" class="separator" alt="">
                            <h1>Extra 15% off</h1>
                            <h3>Beef burger</h3>
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/menuImg4.png" alt="category">
                        <div class="detail">
                            <span>BIG OFFERS</span>
                            <img src="images/separator.svg" class="separator" alt="">
                            <h1>Extra 15% off</h1>
                            <h3>Americano</h3>
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/product0.jpg" alt="category">
                        <div class="detail">
                            <span>BIG OFFERS</span>
                            <img src="images/separator.svg" class="separator" alt="">
                            <h1>Extra 15% off</h1>
                            <h3>Triple scoup ice cream</h3>
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/product2.jpg" alt="category">
                        <div class="detail">
                            <span>BIG OFFERS</span>
                            <img src="images/separator.svg" class="separator" alt="">
                            <h1>Extra 15% off</h1>
                            <h3>small vegetable parcels</h3>
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="dots"></div>
                </div>    
            </div>

            <div class="box-container" id="slider-new-taste">
                <div class="slides">
                    <div class="slide active">
                        <img src="images/food-19.png" alt="category">
                        <div class="detail">
                            <span>new in taste</span>
                            <img src="images/separator.svg" class="separator-taste" alt="">
                            <h1>Tabbouleh Salad</h1>
                            
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/food-7.png" alt="category">
                        <div class="detail">
                            <span>new in taste</span>
                            <img src="images/separator.svg" class="separator-taste" alt="">
                            <h1>Beef bulgogi</h1>
                            
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/cold-beverages.png" alt="category">
                        <div class="detail">
                            <span>new in taste</span>
                            <img src="images/separator.svg" class="separator-taste" alt="">
                            <h1>Two Combination with cream</h1>
                            
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/product1.jpg" alt="category">
                        <div class="detail">
                            <span>new in taste</span>
                            <img src="images/separator.svg" class="separator-taste" alt="">
                            <h1>quadruple scoup ice cream</h1>
                            
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="images/product13.jpg" alt="category">
                        <div class="detail">
                            <span>new in taste</span>
                            <img src="images/separator.svg" class="separator-taste" alt="">
                            <h1>big vegetable parcels</h1>
                            
                            <a href="view_products.php" class="btn">shop now</a>
                        </div>
                    </div>
                    <div class="dots"></div>
                </div>

            </div>
            
        </section>



        <!-- SERCVICES SECTION -->
        <section class="services">
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

    
        <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'components/alert.php'; ?>
   
</body>

</html>