
<header class="header">
    <div class="flex">

        <a href="index.php" class="logo"><img src="images/logo.png" alt="error" width="100px" height="100px"></a>

        <nav class="navbar">
            <ul class="nav-menu">
               <li><a href="index.php">home</a></li>
                <li class="dropdown">
                    <a href="view_products.php">menus</a>
                        <ul class="dropdown-content">
                            <li><a href="view_products.php?category_id=1">foods</a></li>
                            <li><a href="view_products.php?category_id=2">drinks</a></li>
                            <li><a href="view_products.php?category_id=3">desserts</a>
                            </li>
                            <li><a href="view_products.php?category_id=4">groceries</a>
                            </li>
                        </ul>
                </li>
                <li><a href="order.php">order</a></li>
                <li><a href="about.php">about us</a></li>
                <li><a href="contact.php">contact</a></li>
            </ul>

        </nav>
        <div class="icons">
            <i class="fas fa-user" id="user-btn"></i>
            <?php 
                $count_wishlist_items = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
                $count_wishlist_items->execute([$user_id]);
                $total_wishlist_items = $count_wishlist_items->rowCount();
            ?>

            <a href="wishlist.php" class="cart-btn"><i class="fas fa-heart"></i><sup><?=$total_wishlist_items ?></sup></a>

            <?php 
                $count_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                $count_cart_items->execute([$user_id]);
                $total_cart_items = $count_cart_items->rowCount();
            ?>

            <a href="cart.php" class="cart-btn"><i class="fas fa-shopping-cart"></i><sup><?=$total_cart_items ?></sup></a>

            <i class="fas fa-list-plus" id="menu-btn" style="font-size: 2rem;"></i>
        </div>
        <div class="user-box">
            <p>Username : <span><?php echo isset ($_SESSION ['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?></span></p>

            <p>Email : <span><?php echo isset ($_SESSION['user_email']) ? $_SESSION['user_email'] : '-'; ?></span></p>

            <a href="login.php" class="btn">login</a>
            <a href="register.php" class="btn">resgiter</a>

            <form action="" method="post">
                <button type="submit" name="logout" class="logout-btn">log out</button>
            </form>
        </div>
    </div>

</header>