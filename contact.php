
<?php 
    session_start();
    include 'components/connection.php';

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id =' ';
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header("location: login.php");
    }

    if(isset($_POST['send'])) {

        if(isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }else {
            $user_id = null;
        }

       $name = $_POST['name']; 
       $email = $_POST['email']; 
       $number = $_POST['number']; 
       $message = $_POST['message']; 
       
        if($user_id !== null) {
            $insert_message = $conn->prepare("INSERT INTO message (user_id, name, email, number, message) VALUES (?,?,?,?,?)");
            $insert_message->execute([$user_id, $name, $email, $number, $message]);
            $success_msg[] = 'Pesan Berhasil dikirim';

        }else {
            $error_msg[] = 'User belum login, tidak bisa kirim pesan';
        }
       
    }
?>


<style type="text/css">
    <?php include 'css/style.css'; ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawasome/css/all.css">
    <title>Gesge Restaurant - contact us Page</title>
</head>
<body>

    <?php include 'components/header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>contact us</h1>
        </div>
        <div class="title2">
            <a href="index.php">home</a><span> / contact us </span>
        </div>

        <div class="form-container">
            <form action="contact.php" method="post">
                <div class="title">
                    <h1>leave a message</h1>
                    <img src="images/separator.svg" alt="leave a message" class="separator">
                </div>
                <div class="input-field">
                    <p>your name <span>*</span> </p>
                    <input type="text" name="name" required>
                </div>
                <div class="input-field">
                    <p>your email <span>*</span> </p>
                    <input type="email" name="email">
                </div>
                <div class="input-field">
                    <p>your number <span>*</span> </p>
                    <input type="text" name="number">
                </div>
                <div class="input-field">
                    <p>your message <span>*</span> </p>
                    <textarea name="message" id=""></textarea>
                </div>
                <button type="submit" name="send" class="btn">send message</button>
            </form>
        </div>

        <div class="address">
            <div class="title">
                <h1>contact detail</h1>
                <img src="images/separator.svg" alt="" class="separator">
                <p>if you have any question or problem with our restaurant, please contact us.</p>
            </div>
            <div class="box-container">
                <div class="box">
                    <i class="fas fa-map-pin"></i>
                    <div>
                        <h4>address</h4>
                        <p>14101 Grogol, jakarta Barat.</p>
                    </div>
                </div>
                <div class="box">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <h4>phone number</h4>
                        <p>+62 154-786-21</p>
                    </div>
                </div>

                <div class="box">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>email</h4>
                        <p>Gesge@Restaurant.id</p>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>


    <script src="script.js"></script>
    <?php include 'components/alert.php'; ?>
   
</body>

</html>