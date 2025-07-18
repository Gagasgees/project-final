<?php 
    include '../components/connection.php';
    session_start();
    
    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location: login.php');
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
    <title>gesge restaurant admin panel - order canceled page</title>
</head>
<body>
    <?php include '../admin_components/admin_header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>order canceled </h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">dashboard  </a> <span>/ order canceled </span>
        </div>

        <section class="order-container">
            <h1 class="heading">total order canceled </h1>
            <div class="box-container">
                <?php 
                    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE status ='canceled'");
                    $select_orders->execute();

                    if($select_orders->rowCount() > 0) {
                        while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){

                        
                ?>
                <div class="box">
                    <div class="status" style="color: red;"><?= $fetch_orders['status']; ?> </div>

                    <div class="detail">
                        <p>user name : <span><?= $fetch_orders['name']; ?></span></p>
                        <p>user id : <span><?= $fetch_orders['id']; ?></span></p>
                        <p>place on : <span><?= $fetch_orders['date']; ?></span></p>
                        <p>user number : <span><?= $fetch_orders['number']; ?></span></p>
                        <p>user email : <span><?= $fetch_orders['email']; ?></span></p>
                        <p>user price : <span><?= $fetch_orders['price']; ?></span></p>
                        <p>user method : <span><?= $fetch_orders['method']; ?></span></p>
                        <p>user address : <span><?= $fetch_orders['address']; ?></span></p>
                    </div>

                </div>
                

                <?php 
                        }
                    }else{
                        echo '
                            <div class="empty">
                                <p>no order takes placed yet!</p>
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