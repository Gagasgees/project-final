<?php 
    include '../components/connection.php';
    session_start();
    
    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location: login.php');
    }

    // add product in databse
    if (isset ($_POST['publish'])) {
        $id = unique_id();

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

        $status = 'active';

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];       

        $select_image = $conn->prepare("SELECT * FROM product_image WHERE image = ?");
        $select_image->execute([$image]);

        if (isset($image)) {
            if ($select_image-> rowCount() > 0) {
                $warning_msg[] = 'image name repeated';
            }elseif ($image_size > 2000000) {
                $warning_msg[] = 'image size is too large';
            }else{
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
            }
        }else{
            $image = '';
        }
        if ($select_image->rowCount() > 0 AND $image != '') {
            $warning_msg[] = 'please rename your image';
        }else{
            // Insert ke tabel products
            $insert_product = $conn->prepare("INSERT INTO products (name_product, category_id, sub_category_id, product_detail, status) VALUES (?,?,?,?,?)");
            $insert_product ->execute([$name, $category, $subcategory, $content, $status]);

            $product_id = $conn->lastInsertId();

            // Insert ke tabel product_image
            $insert_image = $conn->prepare("INSERT INTO product_image (product_id, image, price) VALUES(?,?,?)");
            $insert_image ->execute([$product_id, $image, $price]);

            $success_msg = 'product inserted successfully';
        }
    }


    // save product in databse as draft
    if (isset ($_POST['draft'])) {
        $id = unique_id();

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

        $status = 'deactive';

       $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];       

        $select_image = $conn->prepare("SELECT * FROM product_image WHERE image = ?");
        $select_image->execute([$image]);

        if (isset($image)) {
            if ($select_image-> rowCount() > 0) {
                $warning_msg[] = 'image name repeated';
            }elseif ($image_size > 2000000) {
                $warning_msg[] = 'image size is too large';
            }else{
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
            }
        }else{
            $image = '';
        }
        if ($select_image->rowCount() > 0 AND $image != '') {
            $warning_msg[] = 'please rename your image';
        }else{
            // Insert ke tabel products
            $insert_product = $conn->prepare("INSERT INTO products (name_product, category_id, sub_category_id, product_detail, status) VALUES (?,?,?,?,?)");
            $insert_product ->execute([$name, $category, $subcategory, $content, $status]);

            $product_id = $conn->lastInsertId();

            // Insert ke tabel product_image
            $insert_image = $conn->prepare("INSERT INTO product_image (product_id, image, price) VALUES(?,?,?)");
            $insert_image ->execute([$product_id, $image, $price]);
            $success_msg = 'product saved as draft successfully';
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
    <title>gesge restaurant admin panel - add product page</title>
</head>
<body>
    <?php include '../admin_components/admin_header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>add product</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">dashboard  </a> <span>/ add product</span>
        </div>

        <section class="form-container">
            <h1 class="heading">add product</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-field">
                    <label>product name <sup>*</sup> </label>
                    <input type="name" name="name" maxlength="100" required placeholder="add product name">
                </div>
                <div class="input-field">
                    <label>product price <sup>*</sup> </label>
                    <input type="number" name="price" maxlength="100" required placeholder="add product name">
                </div>
                <div class="input-field">
                    <label>category product <sup>*</sup> </label>
                    <select name="category" required>
                        <option value="">-- Select Category --</option>
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
                    <label>sub category product <sup>*</sup> </label>
                    <select name="subcategory" required>
                        <option value="">-- Select Sub Category --</option>
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
                    <label>product detail <sup>*</sup> </label>
                    <textarea name="content" required maxlength="10000" required placeholder="write product description"></textarea>
                </div>
                <div class="input-field">
                    <label>product image <sup>*</sup> </label>
                   <input type="file" name="image" accept="image/*" required>
                </div>

                <div class="flex-btn">
                    <button type="submit" name="publish" class="btn">publish product</button>
                    <button type="submit" name="draft" class="btn">save as draft</button>
                </div>
            </form>
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


