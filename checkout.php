
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

   if (isset($_POST['place_order'])) {
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $flat = $_POST['flat'];
        $flat = filter_var($flat, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $street = $_POST['street'];
        $street = filter_var($street, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $city = $city['city'];
        $city = filter_var($city, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $country = $_POST['country'];
        $country = filter_var($country, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pincode = $_POST['pincode'];
        $pincode = filter_var($pincode, FILTER_SANITIZE_NUMBER_INT);
        $address = "$flat, $street, $city, $country, $pincode";
        $address_type = $_POST['address_type'];
        $address_type = filter_var($address_type, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $method = $_POST['method'];
        $method = filter_var($method, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $varify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $varify_cart->execute([$user_id]);

        if (isset($_GET['get_id'])) {
            $get_product = $conn -> prepare("SELECT * FROM products WHERE id = ?  LIMIT 1");
            $get_product -> execute([$_GET['get_id']]);

            if ($get_product->rowCount() > 0 ) {
                while($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)) {
                    $pid = unique_id();
                    $insert_order = $conn->prepare("INSERT INTO `orders` (id, user_id, name, number, email, address, address_type, method, product_id, name_product, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_order->execute([$id, $user_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['name_product'], $fetch_p['price'],1]);
                }
            }else{
                $warning_msg[] = 'something went wrong';
            }
        }elseif($varify_cart->rowCount()> 0) {
            while($f_cart = $varify_cart->fetch(PDO::FETCH_ASSOC)) {
                $id = unique_id();
                $insert_order = $conn->prepare("INSERT INTO `orders` (id, user_id, name, number, email, address, address_type, method, product_id, name_product, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
                $insert_order->execute([$id, $user_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['name_product'], $f_cart['price'], $f_cart['qty']]);

                
            }
            
            if($insert_order) {
                $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $delete_cart_id->execute([$user_id]);

                
            }
        }else{
            $warning_msg[] = 'something went wrong';
        }

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
  <title>Gesge Restaurant - checkout Page</title>
</head>
<body>

    <?php include 'components/header.php'; ?>

    <div class="main">
    <div class="banner">
        <h1>checkout summary</h1>
    </div>
    <div class="title2">
        <a href="index.php">Home</a><span> / checkout summary</span>
    </div>

    <section class="checkout">
        <div class="title">
            <h1>checkout summary</h1>
            <img src="images/separator.svg" alt="" class="separator">
            <p>check back your orders and don't forget input your data for complete this order !!</p>
        </div>

        <div class="row">
            <form action="" method="post">
                <h3>billing details</h3>
                

                <div class="flex">
                    <div class="box">
                        <div class="input-field">
                            <p>your name <span>*</span></p>
                            <input type="text" name="name" maxlength="50" placeholder="Enter Your Name" class="input">
                        </div>
                        <div class="input-field">
                            <p>your number <span>*</span></p>
                            <input type="number" name="number" maxlength="50" placeholder="Enter Your Number" class="input">
                        </div>
                        <div class="input-field">
                            <p>your email <span>*</span></p>
                            <input type="email" name="email" maxlength="50" placeholder="Enter Your Email" class="input">
                        </div>
                        <div class="input-field">
                            <p>payment method <span>*</span></p>
                            <select name="method" id="" class="input">
                                <option value="cash on delivery">cash on delivery</option>
                                <option value="credit or debit card">credit or debit card</option>
                                <option value="dana">dana</option>
                                <option value="shopeepay">shopeePay</option>
                                <option value="gopay">gopay</option>
                                <option value="ovo">ovo</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <p>address type <span>*</span></p>
                            <select name="address_type" id="" class="input">
                                <option value="home">home</option>
                                <option value="office">office</option>                            
                            </select>
                        </div>
                    </div>

                    <div class="box">
                        <div class="input-field">
                            <p>address line 01 <span>*</span></p>
                            <input type="text" name="flat" required maxlength="50" placeholder="e.g flat & building number" class="input">
                        </div>
                        <div class="input-field">
                            <p>address line 02 <span>*</span></p>
                            <input type="text" name="street" required maxlength="50" placeholder="e.g street home" class="input">
                        </div>
                        <div class="input-field">
                            <p>city name <span>*</span></p>
                            <input type="text" name="city" required maxlength="50" placeholder="Enter Your City" class="input">
                        </div>
                        <div class="input-field">
                            <p>country name <span>*</span></p>
                            <input type="text" name="country" required maxlength="50" placeholder="Enter Your Country" class="input">
                        </div>
                        <div class="input-field">
                            <p>pincode <span>*</span></p>
                            <input type="text" name="pincode" required maxlength="6" placeholder="114455" min="0" max="999999" class="input">
                        </div>
                    </div>
                </div>
                <button type="submit" name="place_order" class="btn">place order</button>
            </form>

            <div class="summary">
                <h3>my bag</h3>
                <div class="box-container">

                    <?php 
                        $grand_total = 0;
                        if (isset($_GET['get_id'])) {
                            $select_get = $conn->prepare("SELECT p.id AS product_id, p.name_product, p.product_detail, pi.price, pi.image, c.name AS category_name, sc.name AS subcategory_name FROM products p JOIN product_image pi ON pi.product_id = p.id JOIN categories c ON p.category_id = c.id JOIN sub_categories sc ON p.sub_category_id = sc.id WHERE p.id = ? ");
                            $select_get->execute([$_GET['get_id']]);
                            
                            while ($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
                                $sub_total = $fetch_get['price'];
                                $grand_total+=$sub_total;
                           
                    ?>

                    <div class="flex">

                        <?php 
                            $sub_name = $fetch_get['subcategory_name'];
                            $category = strtolower($fetch_get['category_name']);
                            $sub_folder = strtolower($sub_name);
                            $imagePath = "image/{$category}/{$sub_folder}/" . $fetch_get['image'];
                        ?>
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($fetch_get['name_product']) ?>" class="image">

                        <div>
                            <h3 class="name"><?= $fetch_get['name_product'] ?></h3>

                            <p class="price">$<?= number_format($fetch_get['price'], 2, '.') ?></p>
                        </div>
                    </div>

                    <?php 
                            }
                        } else {
                            $select_cart = $conn->prepare("SELECT cr.user_id AS cart_id, p.id AS product_id, p.name_product, pi.price, pi.image, c.name AS category_name, sc.name AS subcategory_name, cr.qty FROM cart cr JOIN products p ON cr.product_id =p.id JOIN product_image pi ON p.id  = pi.product_id JOIN categories c ON p.category_id = c.id JOIN sub_categories sc ON p.sub_category_id = sc.id WHERE cr.user_id = ?");

                            $select_cart->execute([$user_id]);
                            if ($select_cart->rowCount() > 0 ) {
                                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC))
                                {
                                   

                                    $sub_total = ($fetch_cart['qty'] * $fetch_cart ['price']);

                                    $grand_total += $sub_total;
                                
                    ?>

                    <div class="flex">
                         <?php 
                            $sub_name = $fetch_cart['subcategory_name'];
                            $category = strtolower($fetch_cart['category_name']);
                            $sub_folder = strtolower($sub_name);
                            $imagePath = "image/{$category}/{$sub_folder}/" . $fetch_cart['image'];
                        ?>
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($fetch_cart['name_product']) ?>" class="image">

                       
                        <h3 class="name"><?= $fetch_cart['name_product'] ?></h3>

                        <p class="price">$<?= number_format($fetch_cart['price'], 2, '.') ?> X <?=$fetch_cart['qty']; ?></p>
                        
                    </div>

                    <?php 
                                }
                            } else {
                                echo '<p class="empty">your cart is empty</p></p>y';
                            }
                           
                        }
                    ?>
                </div>
                <div class="grand-total"><span>total amount empty: </span>$<?= $grand_total?>/-</div>
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