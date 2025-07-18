
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
        $category_id = $_POST['category_id'];
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

        header("location: view_products.php?category_id=" .urlencode($category_id));
        exit;
    }



    // adding products in cart

    if (isset($_POST['add_to_cart'])) {
        $id = unique_id();
        $product_id = $_POST['product_id'];
        $category_id = $_POST['category_id'];

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

        header("location: view_products.php?category_id=" . urlencode($category_id));
        exit;
    }

    $category_id = $_GET['category_id'] ?? $_POST['category_id'] ?? null;   

    if (!is_numeric($category_id)) {
        header("location: index.php");
        exit;
    }

    

    $sql = "SELECT p.id, p.name_product, p.sub_category_id, pi.image, pi.price, sc.name AS sub_category_name, c.name AS category_name 
        FROM products p 
        JOIN product_image pi ON p.id = pi.product_id 
        JOIN sub_categories sc ON p.sub_category_id = sc.id 
        JOIN categories  c ON p.category_id = c.id
        WHERE p.category_id = :category_id AND p.status = 'active'
        ORDER BY sc.name, p.name_product ASC";

    $select_products = $conn->prepare($sql);
    $select_products->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $select_products->execute();
    $fetch_products = $select_products->fetchAll(PDO::FETCH_ASSOC);
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
  <title>Gesge Restaurant - Menus</title>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="main">
    <div class="banner">
        <h1>Menus</h1>
    </div>
    <div class="title2">
        <a href="index.php">Home</a><span> / Menus</span>
    </div>

    <section class="products">

        <?php
        $current_sub = null;
        foreach ($fetch_products as $product):
            $sub_name = $product['sub_category_name'];
            $category = strtolower($product['category_name']);
            $sub_folder = strtolower($sub_name);
            $imagePath = "image/{$category}/{$sub_folder}/" . $product['image'];

            // Cek subkategori baru
            if ($current_sub !== $sub_name):

                if( $current_sub !== null): ?>
                </div>
                <?php endif;
                $current_sub = $sub_name;                
            
        ?>
            
            <h2 class="subcategory-title"><?= htmlspecialchars(ucwords($sub_name)) ?></h2>
            <img src="images/separator.svg" class="separator">               
            
            <div class="box-container">
                <?php endif; ?>                
                
                <form action="view_products.php" method="post" class="box">
                    <input type="hidden" name="category_id" value="<?= $category_id ?> ">
                       
                    <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($product['name_product']) ?>" class="img">

                    <div class="button">
                        <button type="submit" name="add_to_cart"><i class="fas fa-shopping-cart"></i></button>
                        <button type="submit" name="add_to_wishlist"><i class="fas fa-heart"></i></button>
                        <a href="view_page.php?pid=<?= $product['id'] ?>"><i class="fas fa-eye"></i></a>
                    </div>

                    <h3 class="name"><?=htmlspecialchars($product['name_product']) ?></h3>
                    <input type="hidden" name="product_id" value="<?=$product['id']; ?>">

                    <div class="flex">
                        <p class="price">Price $<?= number_format($product['price'], 2, '.') ?></p>
                        
                        <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
                    </div>
                    <a href="checkout.php?get_id=<?= $product['id']; ?>" class="btn">buy now</a>
                </form>
                <?php endforeach; ?>
            </div>
        
    </section>


    <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'components/alert.php'; ?>

</body>

</html>