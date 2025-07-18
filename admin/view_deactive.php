<?php 
    include '../components/connection.php';
    session_start();
    
    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location: login.php');
    }

    //DELETE PRODUCT
    if (isset($_POST['delete'])) {
        $p_id = $_POST['product_id'];
        $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);

        // ambil data gambar dari product_image
        $select_image = $conn->prepare("SELECT image FROM product_image WHERE product_id = ?");
        $select_image->execute([$p_id]);
        $fetch_image = $select_image->fetch(PDO::FETCH_ASSOC);

        if ($fetch_image && isset ($fetch_image['image'])) {
            $image_name =  $fetch_image['image'];
        

            // ambil nama kategori dari subkategori
            $select_cat_sub = $conn->prepare("SELECT
                                                categories.name AS category_name,
                                                sub_categories.name AS sub_name
                                                FROM products 
                                                JOIN categories ON products.category_id = categories.id
                                                JOIN sub_categories ON products.sub_category_id = sub_categories.id WHERE products.id = ?");
            
            $select_cat_sub->execute([$p_id]);
            $fetch_cat_sub = $select_cat_sub->fetch(PDO::FETCH_ASSOC);

            // hapus gambar dari folder jika ada
            $image_path = "../image/" .strtolower($fetch_cat_sub['category_name'])."/".strtolower($fetch_cat_sub['sub_name']). "/" .$image_name;
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            // hapus dari product_image
            $delete_image = $conn->prepare("DELETE FROM product_image WHERE product_id = ?");
            $delete_image -> execute([$p_id]);

            // hapus dari product
            $delete_product = $conn->prepare("DELETE FROM  products WHERE id = ?");
            $delete_product ->execute([$p_id]);

            $success_msg[] = 'product deleted successfully';
        }
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
    <title>gesge restaurant admin panel - all peoducts page</title>
</head>
<body>
    <?php include '../admin_components/admin_header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>all products</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">dashboard  </a> <span>/ all products</span>
        </div>

        <section class="show-post">
            <h1 class="heading">all products</h1>
            <div class="box-container">
                <?php 
                    $select_products = $conn->prepare("SELECT products.id,
                                                        products.name_product,
                                                        product_image.image,
                                                        product_image.price,
                                                        products.status,
                                                        categories.name AS category_name,
                                                        sub_categories.name AS sub_name
                                                        FROM products
                                                        JOIN product_image ON products.id = product_image.product_id JOIN categories ON products.category_id = categories.id JOIN sub_categories ON products.sub_category_id = sub_categories.id WHERE status = 'deactive'");
                    $select_products -> execute();

                    if ($select_products->rowCount() > 0) {
                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC))
                        {
                            $img_path = "../image/" .strtolower($fetch_products['category_name'])."/".strtolower($fetch_products['sub_name'])."/" .$fetch_products['image'];
                        
                ?>
                <form action="" method="post" class="box">
                    <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                    
                    <img src="<?= $img_path ?>" class="image">
                    
                    
                    <div class="status" style="color: <?php if($fetch_products['status']=='active'){echo "green";}else{echo "red";} ?>;"><?= $fetch_products['status']; ?></div>
                    <div class="title"><?= $fetch_products['name_product']; ?></div>
                    <div class="category">Category : <?= $fetch_products['category_name']; ?></div>
                    <div class="sub-category">Sub Category : <?= $fetch_products['sub_name']; ?></div>
                    <div class="price">$<?= $fetch_products['price']; ?>/-</div>

                    <div class="flex-btn">
                        <a href="edit_product.php?id=<?= $fetch_products['id']; ?>" class="btn">edit</a>
                        <button type="submit" name="delete" class="btn" onclick="return confirm('delete this product');">delete</button>
                        <a href="read_product.php?id=<?= $fetch_products['id']; ?>" class="btn">view</a>
                    </div>
                </form>
                <?php 
                            
                        }
                    }else{
                        echo '
                            <div class="empty">
                                <p>no product added yet! <br> <a href="add_product.php" style="margin-top: 1.5rem;" class="btn">add product</a></p>
                            </div>
                        ';
                    }
                ?>
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