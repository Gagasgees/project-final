<?php 
    session_start();
    include 'components/connection.php';

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id =' ';
    }

    $message = [];

    if (isset($_POST['submit'])) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $pass = $_POST['pass'];

        if(!$email) {
            $message[] = 'Format email tidak valid';
        } else {
            $select_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $select_user->execute([$email]);

            if ($select_user->rowCount() > 0) {
                $row = $select_user->fetch(PDO::FETCH_ASSOC);

                if (password_verify($pass, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['name'];
                    $_SESSION['user_email'] = $row['email'];

                    header('location: index.php');
                    exit;
                } else {
                    $message[] = 'password salah';
                }
            } else {
                $message[] = 'Email tidak ditemukan';
            }
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
    <title>Gesge Restaurant - login

    </title>
</head>
<body>
    <div class="main-container">
        <div class="form-container">
            <div class="title">
                <h1>login now</h1>
                <img src="images/separator.svg" alt="" class="separator">
                <p>Login Now for accses our restaurant!</p>
            </div>

            <form action="" method="post">
                <div class="input-field">
                    <p>your email <span>*</span></p>
                    <input type="email" name="email" required placeholder="enter your email" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
                <div class="input-field">
                    <p>your password <span>*</span></p>
                    <input type="password" name="pass" required placeholder="enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
                <input type="submit" name="submit" value="login now" class="btn">
                <p class="title-account">do not have an account ? <a href="register.php">Register Now</a> </p>
            </form>
        </div>
    </div>
   

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'components/alert.php'; ?>
   
</body>
</html>