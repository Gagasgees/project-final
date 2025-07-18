
<?php 
    session_start();
    include 'components/connection.php';

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }else {
        $user_id = '';
    };

    if (isset($_POST['logout'])) {
        session_destroy();
        header("location: login.php");
    }

    if (isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    }else {
        $get_id = '';
        header('location: order.php');
    }

    if (isset($_POST['cancel'])) {
        $update_order = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $update_order->execute(['canceled', $get_id]);
        header('location: order.php');
        exit;
    }


   
?>

<style>
<?php include 'css/style.css'; ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="fontawasome/css/all.css"/>
  <title>Gesge Restaurant - order detail</title>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="main">
    <div class="banner">
        <h1>order detail</h1>
    </div>
    <div class="title2">
        <a href="index.php">Home</a><span> / order detail</span>
    </div>

    <section class="order-detail">
        <div class="title">
            <h1>my orders</h1>
            <img src="images/separator.svg" alt="" class="separator">
            <p>This is your orders detail, don't forget check back your orders !!</p>
        </div>

        <div class="box-container">
            <?php 
                $grand_total = 0;
                $select_orders = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
                $select_orders->execute([$get_id]);
                if ($select_orders->rowCount() > 0) {
                    while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        $select_products = $conn->prepare(" SELECT p.id AS product_id, p.name_product, p.product_detail, pi.price, pi.image, c.name AS category_name, sc.name AS subcategory_name FROM products p JOIN product_image pi ON pi.product_id = p.id JOIN categories c ON p.category_id = c.id JOIN sub_categories sc ON p.sub_category_id = sc.id WHERE p.id = ? LIMIT 1 ");
                        $select_products->execute([$fetch_order['product_id']]);
                        if ($select_products->rowCount() > 0) {
                            while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                                $sub_total = ($fetch_order['price'] * $fetch_order['qty']);
                                $grand_total+=$sub_total;

                            
            ?>

            <div class="box">
                <div class="col">           
                    <p class="title"><i class="fas fa-calendar-alt"></i>
                    <?=$fetch_order['date']; ?></p>
                    <?php 
                        $sub_name = $fetch_product['subcategory_name'];
                        $category = strtolower($fetch_product['category_name']);
                        $sub_folder = strtolower($sub_name);
                        $imagePath = "image/{$category}/{$sub_folder}/" . $fetch_product['image'];
                    ?>
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($fetch_get['name_product']) ?>" class="image">
                    <p class="price">$<?= number_format($fetch_order['price'], 2, '.') ?> x <?=$fetch_order['qty']; ?></p>
                    <h3 class="name"><?=$fetch_product['name_product']; ?></h3>
                    <p class="grand-total">Total Amount Payable : <span> $ <?= $grand_total; ?></span></p>
                </div>
                    
                <div class="col">                   
                   <p class="title">billing address</p>
                   <p class="user"><i class="fas fa-user"></i><?= $fetch_order['name']; ?></p>
                   <p class="user"><i class="fas fa-phone"></i><?= $fetch_order['number']; ?></p>
                   <p class="user"><i class="fas fa-envelope"></i><?= $fetch_order['email']; ?></p>
                   <p class="user"><i class="fas fa-map"></i><?= $fetch_order['address']; ?></p>

                   <p class="title">status</p>
                   <p class="status" style="color:<?php if ($fetch_order['status'] =='delevered'){echo'green';}elseif($fetch_order['status']=='canceled') {echo 'red';}else{echo 'orange';}?>"><?=$fetch_order['status'] ?></p>

                   <?php if ($fetch_order['status']=='canceled') { ?>
                        <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn"> order again</a>
                    <?php }else{ ?>
                        <form action="" method="post">
                            <button type="submit" name="cancel" class="btn" onclick="return confirm('do you want to cancel this order')">cancel order</button>
                        </form>
                    <?php } ?>
                </div>                        
            </div>

            <?php 
                            }
                        } else {
                            echo '<p class="empty">products not found</p>';
                        }

                    }
                }else{
                    echo '<p class="empty">no order found</p>';
                }
            ?>
        </div>
    </section>
    


    <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'components/alert.php'; ?>

</body>

</html>