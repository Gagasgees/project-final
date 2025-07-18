<?php 
    include '../components/connection.php';
    session_start();
    
    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location: login.php');
    }


    //UPDATE PRODUCT
    if (isset($_POST['update'])) {
        $post_id = $_GET['id'];

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $price = $_POST['price'];
        $price = filter_var($price, FILTER_SANITIZE_STRING);

        $category = $_POST['category'];
        $category = filter_var($category, FILTER_SANITIZE_STRING);

        $subcategory = $_POST['subcategory'];
        $subcategory = filter_var($subcategory, FILTER_SANITIZE_STRING);

        $content = $_POST['content'];
        $content = filter_var($content, FILTER_SANITIZE_STRING);

        $status = $_POST['status'];
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        $product_id = $_POST['product_id'];

        //update product
        $update_product = $conn->prepare("UPDATE products SET name_product = ?, category_id = ?, sub_category_id = ?, product_detail = ?, status = ? WHERE id = ?");
        $update_product->execute([$name, $category, $subcategory, $content, $status, $post_id ]);       

        $success_msg[] = 'product updated';

        $old_image = $_POST['old_image'];
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];

        if (!empty($image)) {    
            $select_category = $conn->prepare("SELECT name FROM categories WHERE id = ?");
            $select_category->execute([$category]);
            $category_name = $select_category->fetchColumn();

            $select_sub = $conn->prepare("SELECT name FROM sub_categories WHERE id = ? ");
            $select_sub->execute([$subcategory]);
            $sub_name = $select_sub->fetchColumn();

            $folder_path = "../image/$category_name/$sub_name/";

            
            if (!file_exists($folder_path)) {
                mkdir($folder_path, 0777, true);
            }
            move_uploaded_file($image_tmp_name, $folder_path . $image);    

            $update_img = $conn->prepare("UPDATE product_image SET image = ?, price = ? WHERE product_id = ?");
            $update_img->execute([$image, $price, $product_id]);            
        }else {
            $update_image = $conn->prepare("UPDATE product_image SET price = ? WHERE product_id =?");
            $update_image->execute([$price, $product_id]);
        }
        $success_msg[] = 'image updated';
        
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

            header('location:view_product.php');
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
    <title>gesge restaurant admin panel - edit peoduct page</title>
</head>
<body>
    <?php include '../admin_components/admin_header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>edit products</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">dashboard  </a> <span>/ edit products</span>
        </div>

        <section class="edit-post">
            <h1 class="heading">edit products</h1>
            <?php 
                $post_id = $_GET['id'];

                $select_product = $conn->prepare("SELECT products.id,
                                                        products.name_product,
                                                        product_image.image,
                                                        product_image.price,
                                                        products.product_detail,
                                                        products.status,
                                                        categories.name AS category_name,
                                                        sub_categories.name AS sub_name
                                                        FROM products
                                                        JOIN product_image ON products.id = product_image.product_id JOIN categories ON products.category_id = categories.id JOIN sub_categories ON products.sub_category_id = sub_categories.id WHERE products.id = ?");
                $select_product->execute([$post_id]);

                if ($select_product->rowCount() > 0) {
                    while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {

                   
            ?>
            <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="old_image" value="<?= $fetch_product['image']; ?>">
                    <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">

                    <div class="input-field">
                        <label>update status</label>
                        <select name="status" id="">
                            <option selected disabled value="<?= $fetch_product['status'] ?>"><?= $fetch_product['status'] ?></option>
                            <option value="active">active</option>
                            <option value="deactive">deactive</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>product name</label>
                        <input type="text" name="name" value="<?= $fetch_product['name_product'] ?>">
                    </div>
                    <div class="input-field">
                        <label>product price</label>
                        <input type="number" name="price" value="<?= $fetch_product['price'] ?>">
                    </div>
                    <div class="input-field">
                        <label>category product</label>
                        <select name="category" >
                            <option selected disabled value="<?= $fetch_product['category_name'] ?>"><?= $fetch_product['category_name'] ?></option>
                            <?php 
                                $select_category = $conn->prepare("SELECT * FROM categories");
                                $select_category -> execute();
                                while($fetch_category = $select_category->fetch(PDO:: FETCH_ASSOC)) {
                                    echo '<option value="'.$fetch_category['id'].'">'.$fetch_category['name'].'</option>';
                                }
                            ?>

                        </select>                   
                    </div>
                    <div class="input-field">
                        <label>sub category product</label>
                        <select name="subcategory">
                            <option selected disabled value="<?= $fetch_product['sub_name'] ?>"><?=  $fetch_product['sub_name'] ?></option>
                            <?php 
                                $select_subcategory = $conn->prepare("SELECT * FROM sub_categories");
                                $select_subcategory -> execute();
                                while($fetch_subcategory = $select_subcategory->fetch(PDO:: FETCH_ASSOC)) {
                                    echo '<option value="'.$fetch_subcategory['id'].'">'.$fetch_subcategory['name'].'</option>';
                                }
                            ?>

                        </select>                   
                    </div>
                    <div class="input-field">
                        <label>product description</label>
                        <textarea name="content"><?= $fetch_product['product_detail'] ?></textarea>
                    </div>
                    <div class="input-field">
                        <label>product image</label>
                        <input type="file" name="image" accept="image/*">
                        <img src="../image/<?=
                        strtolower($fetch_product['category_name']); ?>/<?=
                        strtolower($fetch_product['sub_name']); ?>/<?=
                        $fetch_product['image']; ?>">
                    </div>

                    <div class="flex-btn">
                        <button type="submit" name="update" class="btn">update product</button>
                        <a href="view_product.php" class="btn">go back</a>
                        <button type="submit" name="delete" class="btn">delete product</button>
                    </div>
                </form>
            </div>
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