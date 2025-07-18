
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

   



    // adding products in cart

    if (isset($_POST['add_to_cart'])) {
        $id = unique_id();
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['user_id'];

        $qty = $_POST['qty'];
        $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);

        $qty = 1;
        $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
        if(!$qty === null || $qty === false || $qty <= 0) {
            $qty = 1;
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

            $delete_cart = $conn->prepare("DELETE FROM wishlist WHERE user_id =? AND product_id = ?");
            $delete_cart->execute([$user_id, $product_id]);
        }

        header("location: wishlist.php");
        exit;
    }

    // tampilkan pesan 
    if (isset($_SESSION['success_msg'])) {
        echo "<script>alert(".json_encode($_SESSION['success_msg']).");</script>";
        unset($_SESSION['success_msg']);
    }

    if (isset($_SESSION['warning_msg'])) {
        echo "<script>alert(".json_encode($_SESSION['warning_msg']).");</script>";
        unset($_SESSION['warning_msg']);
    }


    // delete item from wishlist
    if (isset($_POST['delete_item'])) {
        $wishlist_id = $_POST['wishlist_id'];
        $wishlist_id = filter_var($wishlist_id, FILTER_VALIDATE_INT);

        $varify_delete_items = $conn->prepare("SELECT * FROM wishlist WHERE id = ?");
        $varify_delete_items ->execute([$wishlist_id]);

        if ($varify_delete_items->rowCount()>0) {
            $delete_wishlist_id = $conn->prepare("DELETE FROM wishlist WHERE id = ?");
            $delete_wishlist_id ->execute([$wishlist_id]);
            $success_msg[] = "wishlist item delete successfully";
        }else {
            $warning_msg[] = "wishlist item already delete";
        }

        header("location: wishlist.php");
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
  <title>Gesge Restaurant - Whislist Page</title>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="main">
    <div class="banner">
        <h1>Wishlist</h1>
    </div>
    <div class="title2">
        <a href="index.php">Home</a><span> / wishlist</span>
    </div>

    <section class="products"> 
        <h1 class="title">products added in wishlist</h1> 
        <img src="images/separator.svg" alt="" class="separator">
        <div class="box-container">
            <?php 
                $grand_total = 0;
                $select_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id =?");
                $select_wishlist->execute([$user_id]);
                if ($select_wishlist->rowCount() > 0) {
                    while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
                        $select_products = $conn->prepare("SELECT w.id AS wishlist_id, p.id AS product_id, p.name_product, pi.price, pi.image, c.name AS category_name, sc.name AS subcategory_name FROM wishlist w JOIN products p ON w.product_id =p.id JOIN product_image pi ON p.id  = pi.product_id JOIN categories c ON p.category_id = c.id JOIN sub_categories sc ON p.sub_category_id = sc.id WHERE w.id = ? ");
                        $select_products->execute([$fetch_wishlist['id']]);
                        if ($select_products->rowCount()> 0) {
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);


                       
            ?>
            <form method="POST" action="" class="box">
                <input type="hidden" name="wishlist_id" value="<?=$fetch_wishlist['id']; ?>">

                <?php 
                    $sub_name = $fetch_products['subcategory_name'];
                    $category = strtolower($fetch_products['category_name']);
                    $sub_folder = strtolower($sub_name);
                    $imagePath = "image/{$category}/{$sub_folder}/" . $fetch_products['image'];
                ?>

                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($fetch_products['name_product']) ?>" class="img">

                <div class="wishlist-button">
                    <button type="submit" name="add_to_cart"><i class="fas fa-shopping-cart"></i></button>
                    <a href="view_page.php?pid=<?= $fetch_products['product_id'] ?>"><i class="fas fa-eye"></i></a>
                    <button type="submit" name="delete_item" onclick="return confirm('delete this item')"><i class="fas fa-x"></i></button>
                </div>
                <h3 class="wishlist-name"><?=$fetch_products ['name_product']; ?></h3>
                <input type="hidden" name="product_id" value="<?=$fetch_products['product_id']; ?>">

                <div class="flex">
                    <p class="wishlist-price">Price $<?= number_format($fetch_products['price'], 2, '.') ?></p>
                </div>
                <a href="checkout.php?get_id=<?=$fetch_products['product_id']; ?>" class="btn">buy now</a>
            </form>

            <?php 
                        $grand_total+=$fetch_wishlist['price'];
                        }
                    }
                } else {
                    echo '<p class="empty">no products added yet!</p>';
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