
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

    // adding products in wishlist
    if (isset($_POST['add_to_wishlist'])) {
        $id = unique_id();
        $product_id = $_POST['product_id'];
        $qty = isset($_POST['qty']) ? (int) $_POST ['qty'] : 1;

        $varify_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id =? AND product_id =?");
        $varify_wishlist ->execute([$user_id, $product_id]);

        $cart_num = $conn->prepare("SELECT * FROM cart WHERE user_id =? AND product_id =?");
        $cart_num ->execute([$user_id, $product_id]);

        if ($varify_wishlist->rowCount() > 0) {
            $_SESSION['warning_msg'] = 'product already exist in your wishlist';
        } else if ($cart_num->rowCount() > 0) {
            $_SESSION['warning_msg'] = 'product already exist in your cart';
        } else {
            $select_price = $conn->prepare("SELECT * FROM product_image WHERE product_id =? LIMIT 1");
            $select_price ->execute([$product_id]);
            $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);
            

            $select_name = $conn->prepare("SELECT * FROM products WHERE id =?");
            $select_name->execute([$product_id]);
            $fetch_name = $select_name->fetch(PDO::FETCH_ASSOC);
            

            $insert_wishlist = $conn->prepare("INSERT INTO wishlist (user_id, product_id, name_product, price, qty) VALUES (?,?,?,?,?)");
            $insert_wishlist->execute([$user_id, $product_id, $fetch_name['name_product'], $fetch_price['price'], $qty]);
            $_SESSION['success_msg'] = 'product added to wishlist successfully';
            
        }

        header("location: view_products.php?category_id=1");
        exit;
    }



    // adding products in cart

    if (isset($_POST['add_to_cart'])) {
        $id = unique_id();
        $product_id = $_POST['product_id'];

        $qty = $_POST['qty'];
        $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);

        if(!$qty || $qty <= 0) {
            $_SESSION['warning_msg'] = 'Invalid quantity!';
        }

        $cart_limit = 99;
        $varify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $varify_cart->execute([$user_id, $product_id]);

        $cart_num = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $cart_num->execute([$user_id]);
        $cart_count = $cart_num->rowCount();

        if ($varify_cart->rowCount() > 0) {
            $_SESSION['warning_msg'] = 'product already exist in your cart';
        } else if ($cart_num->rowCount() > $cart_limit) {
            $_SESSION['warning_msg'] = 'cart is full';
        } else {
            $select_price = $conn->prepare("SELECT * FROM product_image WHERE id = ? LIMIT 1");
            $select_price->execute([$product_id]);
            $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

            $select_name = $conn->prepare("SELECT * FROM products WHERE id =?");
            $select_name->execute([$product_id]);
            $fetch_name = $select_name->fetch(PDO::FETCH_ASSOC);

            $insert_cart = $conn->prepare("INSERT INTO cart(user_id, product_id, name_product, price, qty) VALUES (?,?,?,?,?)");
            $insert_cart->execute([$user_id, $product_id, $fetch_name['name_product'], $fetch_price['price'], $qty]);
            $_SESSION['success_msg'] = 'product added to cart successfully';
        }

        header("location: view_products.php?category_id=1");
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
  <title>Gesge Restaurant - Product Detail Page</title>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="main">
    <div class="banner">
        <h1>Product Detail</h1>
    </div>
    <div class="title2">
        <a href="index.php">Home</a><span> / product detail</span>
    </div>

    <section class="view_page">
        <?php 
            if (isset($_GET['pid'])) {
                $pid = $_GET['pid'];
                $select_products = $conn->prepare("SELECT p.id AS product_id, p.name_product, p.product_detail, pi.price, pi.image, c.name AS category_name, sc.name AS subcategory_name FROM products p JOIN product_image pi ON pi.product_id = p.id JOIN categories c ON p.category_id = c.id JOIN sub_categories sc ON p.sub_category_id = sc.id WHERE p.id = ? ");
                $select_products->execute([$pid]);
                if ($select_products->rowCount() > 0) {
                    while($fetch_products = $select_products ->fetch(PDO::FETCH_ASSOC)) {

                   
        ?>
        <form action="" method="post">
            <?php 
                $sub_name = $fetch_products['subcategory_name'];
                $category = strtolower($fetch_products['category_name']);
                $sub_folder = strtolower($sub_name);
                $imagePath = "image/{$category}/{$sub_folder}/" . $fetch_products['image'];
            ?>

                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($fetch_products['name_product']) ?>">

                
                <div class="detail">
                    <div class="price">$<?= number_format($fetch_products['price'], 2, '.') ?></div>
                    <div class="name"><?php echo $fetch_products['name_product']; ?></div>
                    
                    <div class="detail">
                        <p class="product-detail"><?php echo $fetch_products['product_detail']; ?> </p>
                    </div>
                    <input type="hidden" name="product_id" value="<?php echo $fetch_products['product_id']; ?>">
                    <div class="button">
                        <button type="submit" name="add_to_wishlist" class="btn">add to wishlist <i class="fas fa-heart"></i></button>
                        <input type="hidden" name="qty" value="1" min="0" class="quantity">
                        <button type="submit" name="add_to_cart" class="btn">add to cart <i class="fas fa-shopping-cart"></i></button>
                    </div>
                </div>
        </form>
        <?php 
                    }
                }
            }
        ?>

        
    </section>


    <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'components/alert.php'; ?>

</body>

</html>