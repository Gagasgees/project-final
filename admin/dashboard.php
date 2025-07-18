<?php 
    include '../components/connection.php';
    session_start();
    
    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location: login.php');
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!---- Fonawasome ---->
    <link rel="stylesheet" href="../fontawasome/css/all.min.css">
    <link rel="stylesheet"  href="../admin_css/admin_style.css?v=<?php echo time(); ?>">
    <title>gesge restaurant admin panel - dashboard page</title>
</head>
<body>
    <?php include '../admin_components/admin_header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>dashboard</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">home  </a> <span>/ dashboard</span>
        </div>

        <section class="dashboard">
            <h1 class="heading">dashboard</h1>
            <div class="box-container">
                <div class="box">
                    <h3>welcome!</h3>
                    <p><?= $fetch_profile['name']; ?></p>
                    <a href="" class="btn">profile</a>
                </div>

                <div class="box">
                    <?php 
                        $select_product = $conn->prepare("SELECT * FROM products");
                        $select_product->execute();
                        $num_of_products = $select_product->rowCount ();
                    ?>
                    <h3><?= $num_of_products; ?></h3>
                    <p>added products</p>
                    <a href="add_product.php" class="btn">add new products</a>
                </div>

                <div class="box">
                    <?php 
                        $select_active_product = $conn->prepare("SELECT * FROM products WHERE status = ?");
                        $select_active_product->execute(['active']);
                        $num_of_active_products = $select_active_product->rowCount ();
                    ?>
                    <h3><?= $num_of_active_products; ?></h3>
                    <p>total active products</p>
                    <a href="view_active.php" class="btn">view active products</a>
                </div>

                <div class="box">
                    <?php 
                        $select_deactive_product = $conn->prepare("SELECT * FROM products WHERE status = ?");
                        $select_deactive_product->execute(['deactive']);
                        $num_of_deactive_products = $select_deactive_product->rowCount ();
                    ?>
                    <h3><?= $num_of_deactive_products; ?></h3>
                    <p>total deactive products</p>
                    <a href="view_deactive.php" class="btn">view deactive products</a>
                </div>

                <div class="box">
                    <?php 
                        $select_users = $conn->prepare("SELECT * FROM users");
                        $select_users->execute();
                        $num_of_users = $select_users->rowCount ();
                    ?>
                    <h3><?= $num_of_users; ?></h3>
                    <p>registered users</p>
                    <a href="main_user.php" class="btn">view users</a>
                </div>

                <div class="box">
                    <?php 
                        $select_admin = $conn->prepare("SELECT * FROM admin");
                        $select_admin->execute();
                        $num_of_admin = $select_admin->rowCount ();
                    ?>
                    <h3><?= $num_of_admin; ?></h3>
                    <p>registered admin</p>
                    <a href="user_account.php" class="btn">view admin</a>
                </div>

                <div class="box">
                    <?php 
                        $select_message = $conn->prepare("SELECT * FROM message");
                        $select_message->execute();
                        $num_of_message = $select_message->rowCount ();
                    ?>
                    <h3><?= $num_of_message; ?></h3>
                    <p>unread message</p>
                    <a href="admin_message.php" class="btn">view message</a>
                </div>

                <div class="box">
                    <?php 
                        $select_orders = $conn->prepare("SELECT * FROM orders");
                        $select_orders->execute();
                        $num_of_orders = $select_orders->rowCount ();
                    ?>
                    <h3><?= $num_of_orders; ?></h3>
                    <p>total orders placed</p>
                    <a href="order.php" class="btn">view orders</a>
                </div>

                <div class="box">
                    <?php 
                        $select_confirm_orders = $conn->prepare("SELECT * FROM orders WHERE status = 'confirmed'");
                        $select_confirm_orders->execute();
                        $num_of_confirm_orders = $select_confirm_orders->rowCount ();
                    ?>
                    <h3><?= $num_of_confirm_orders; ?></h3>
                    <p>total confirm orders </p>
                    <a href="view_confirm.php" class="btn">view confirm orders</a>
                </div>

                <div class="box">
                    <?php 
                        $select_canceled_orders = $conn->prepare("SELECT * FROM orders WHERE status = 'canceled'");
                        $select_canceled_orders->execute();
                        $num_of_canceled_orders = $select_canceled_orders->rowCount ();
                    ?>
                    <h3><?= $num_of_canceled_orders; ?></h3>
                    <p>total canceled orders </p>
                    <a href="view_canceled.php" class="btn">view canceled orders</a>
                </div>
                
            </div>
        </section>
    </div>

    <!-- sweetalert cdn link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- JS SCRIPT -->
    <script type="text/javascript" src="../admin_js/script.js"></script>

    <!-- ALERT -->
     <?php include '../admin_components/alert.php'; ?>
</body>
</html>