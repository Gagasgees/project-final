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



    if (isset($_GET['id'])){
        $id = $_GET['id'];

        $stmt = $conn->prepare("SELECT products .*,
                                products.name_product,
                                product_image.image,
                                product_image.price,
                                products.status,
                                products.product_detail,
                                categories.name AS category_name,
                                sub_categories.name AS sub_name
                                FROM products
                                JOIN product_image ON products.id = product_image.product_id JOIN categories ON products.category_id = categories.id 
                                JOIN sub_categories ON products.sub_category_id = sub_categories.id
                                WHERE products.id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <title>gesge restaurant admin panel - read peoduct page</title>
</head>
<body>
    <?php include '../admin_components/admin_header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>read products</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">dashboard  </a> <span>/ read products</span>
        </div>

        <section class="read-post">
            <h1 class="heading">read products</h1>
            <?php 
                    
                if ($product) {
                    $img_path = "../image/" .strtolower($product['category_name'])."/".strtolower($product['sub_name'])."/" .$product['image'];
                    if (file_exists($img_path)) {
                        unlink($img_path);
                    }
                        
            ?>
            <form action="" method="post">
                <input type="hidden" name="product_id" value="<?=  $product['id']; ?>">

                <div class="status" style="color: <?php if( $product['status']=='active'){echo "green";}else{echo "red";} ?>"><?=  $product['status']; ?></div>
                
                <img src="<?= $img_path ?>" class="image">
                

                <div class="price">$<?=  $product['price']; ?>/-</div>
                <div class="title"><?=  $product['name_product']; ?></div>
                <div class="category">Category : <?=  $product['category_name']; ?></div>
                <div class="sub-category">Sub Category : <?=  $product['sub_name']; ?></div>
                <div class="content"><?=  $product['product_detail']; ?></div>

                <div class="flex-btn">
                    <a href="edit_product.php?id=<?= $product['id']; ?>" class="btn">edit</a>
                    <button type="submit" name="delete" class="btn" onclick="return confirm('delete this product');">delete</button>
                    <a href="view_product.php?id=<?= $id; ?>" class="btn">go back</a>
                </div>
            </form>
            <?php 
               
               }else{ echo '
                    <div class="empty">
                        <p>no product added yet! <br> <a href="add_product.php" style="margin-top: 1.5rem;" class="btn">add product</a></p>
                    </div>
                ';
               }
                
            ?>
            
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