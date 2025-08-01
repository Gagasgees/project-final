
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


    // update product in cart
    if (isset($_POST['update_cart'])) {
        $cart_id = $_POST['cart_id'];
        $cart_id = filter_var($cart_id, FILTER_VALIDATE_INT);
        $qty = $_POST['qty'];
        $qty = filter_var($qty, FILTER_VALIDATE_INT);

        $update_qty = $conn->prepare("UPDATE cart SET qty = ? WHERE id =?");
        $update_qty ->execute([$qty, $cart_id]);

        $success_msg[] = 'cart quantity updated successfully';
    }

   


    // delete item from cart
    if (isset($_POST['delete_item'])) {
        $cart_id = $_POST['cart_id'];
        $cart_id = filter_var($cart_id, FILTER_VALIDATE_INT);

        $varify_delete_items = $conn->prepare("SELECT * FROM cart WHERE id = ?");
        $varify_delete_items ->execute([$cart_id]);

        if ($varify_delete_items->rowCount()>0) {
            $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE id = ?");
            $delete_cart_id ->execute([$cart_id]);
            $success_msg[] = "cart item delete successfully";
        }else {
            $warning_msg[] = "cart item already delete";
        }

        header("location: cart.php");
        exit;
    }

    
    // empty cart
    if (isset($_POST['empty_cart'])) {
        $varify_empty_item = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $varify_empty_item ->execute([$user_id]);

        if ($varify_empty_item->rowCount() > 0) {
            $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE user_id =? ");
            $delete_cart_id->execute([$user_id]);
            $success_msg[] = "empty successfully";
        }else {
            $warning_msg[] = 'cart item already deleted';
        }
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
  <title>Gesge Restaurant - cart Page</title>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="main">
    <div class="banner">
        <h1>my cart</h1>
    </div>
    <div class="title2">
        <a href="index.php">Home</a><span> / cart</span>
    </div>

    <section class="products"> 
        <h1 class="title">products added in cart</h1> 
        <img src="images/separator.svg" alt="" class="separator">
        <div class="box-container">
            <?php 
                $grand_total = 0;
                $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id =?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $select_products = $conn->prepare("SELECT cr.id AS cart_id, p.id AS product_id, p.name_product, pi.price, pi.image, c.name AS category_name, sc.name AS subcategory_name FROM cart cr JOIN products p ON cr.product_id =p.id JOIN product_image pi ON p.id  = pi.product_id JOIN categories c ON p.category_id = c.id JOIN sub_categories sc ON p.sub_category_id = sc.id WHERE cr.id = ? ");
                        $select_products->execute([$fetch_cart['id']]);
                        if ($select_products->rowCount()> 0) {
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);


                       
            ?>
            <form method="POST" action="" class="box">
                <input type="hidden" name="cart_id" value="<?=$fetch_cart['id']; ?>">

                <?php 
                    $sub_name = $fetch_products['subcategory_name'];
                    $category = strtolower($fetch_products['category_name']);
                    $sub_folder = strtolower($sub_name);
                    $imagePath = "image/{$category}/{$sub_folder}/" . $fetch_products['image'];
                ?>

                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($fetch_products['name_product']) ?>" class="img">            
                
                <h3 class="wishlist-name"><?=$fetch_products ['name_product']; ?></h3>
                <input type="hidden" name="product_id" value="<?=$fetch_products['product_id']; ?>">

                <div class="flex">
                    <p class="wishlist-price">Price $<?= number_format($fetch_products['price'], 2, '.') ?></p>
                    <input type="number" name="qty" required min="1" value="<?=$fetch_cart['qty']; ?>" max="99" maxlength="2" class="qty-cart">

                    <button type="submit" name="update_cart" class="fas fa-edit bxs-edit"></button>
                </div>
                <p class="sub-total">sub total : <span>$<?=$sub_total = ($fetch_cart['qty'] * $fetch_cart['price']) ?></span></p>

                <button type="submit" name="delete_item"  class="btn" onclick="return confirm('delete this item')">delete</button>
            </form>

            <?php 
                        $grand_total+=$sub_total;
                        } else {
                            echo '<p class="empty">products was not found</p>';
                        }
                    }
                } else {
                    echo '<p class="empty">no products added yet!</p>';
                }
            ?>
        </div>    
        <?php
            if ($grand_total !=0) {
        ?>
        <div class="cart-total">
            <p>total amount payable : <span>$ <?= $grand_total; ?></span></p>
            <div class="button">
                <form action="" method="post">
                    <button type="submit" name="empty_cart" class="btn" onclick="return confirm('are you sure to empty your cart')">empty cart</button>
                    
                </form>
                <a href="checkout.php" class="btn">proceed to checkout</a>
            </div>
        </div>
        <?php } ?>
        
    </section>


    <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'components/alert.php'; ?>

</body>

</html>