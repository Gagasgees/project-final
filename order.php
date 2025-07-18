
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
  <title>Gesge Restaurant - order Page</title>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="main">
    <div class="banner">
        <h1>my order</h1>
    </div>
    <div class="title2">
        <a href="index.php">Home</a><span> / order</span>
    </div>

    <section class="orders">
        <div class="title">
            <h1>my orders</h1>
            <img src="images/separator.svg" alt="" class="separator">
            <p>This is your orders don't forget check back your orders !!</p>
        </div>

        <div class="box-container">
            <?php 
                $select_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY date DESC");
                $select_orders->execute([$user_id]);
                if ($select_orders->rowCount() > 0) {
                    while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        $select_products = $conn->prepare(" SELECT p.id AS product_id, p.name_product, p.product_detail, pi.price, pi.image, c.name AS category_name, sc.name AS subcategory_name FROM products p JOIN product_image pi ON pi.product_id = p.id JOIN categories c ON p.category_id = c.id JOIN sub_categories sc ON p.sub_category_id = sc.id WHERE p.id = ? ");
                        $select_products->execute([$fetch_order['product_id']]);
                        if ($select_products->rowCount() > 0) {
                            while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {

                            
            ?>

            <div class="box"<?php if($fetch_order['status']=='cancle'){echo 'style="border:2px solid red*;';}?>>

            <a href="view_order.php?get_id=<?=$fetch_order['id']; ?>">

                <p class="date"><i class="fas fa-calender-alt"></i><span><?=$fetch_order['date']; ?></span></p>

                <?php 
                    $sub_name = $fetch_product['subcategory_name'];
                    $category = strtolower($fetch_product['category_name']);
                    $sub_folder = strtolower($sub_name);
                    $imagePath = "image/{$category}/{$sub_folder}/" . $fetch_product['image'];
                ?>
                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($fetch_get['name_product']) ?>" class="image">
                
                <div class="row">
                    <h3 class="name"><?=$fetch_product['name_product']; ?></h3>
                    <p class="price">$<?= number_format($fetch_order['price'], 2, '.') ?> x <?=$fetch_order['qty']; ?></p>
                    <p class="status" style="color:<?php if($fetch_order['status']=='delivered') {
                        echo 'green';}elseif($fetch_order['status']=='canceled'){echo 'red';}else{echo 'orange';} ?>"><?= $fetch_order['status']; ?>
                    </p>
                    
                </div>
            </a>
            </div>

            <?php 
                            }
                        }

                    }
                }else{
                    echo '<p class="empty">no order takes place yet!</p>';
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