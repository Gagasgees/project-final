
<?php 
    session_start();
    include 'components/connection.php';

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id =' ';
    }

    // register now
    if (isset($_POST['submit'])) {
        $id = unique_id();
        $name = htmlspecialchars($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $pass = $_POST['pass'];
        $cpass = $_POST['cpass'];

        if (!$email) {
            $message[] = 'Invalid email format';
        } elseif ($pass !== $cpass) {
            $message[] = 'Password and Confirm Password do not match';
        } else {
            // Hash the password before storing
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

            // check if user already exists
            $select_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $select_user ->execute([$email]);

            if  ($select_user->rowCount() > 0) {
                $message[] = 'Email already exists';
                echo 'email already exists';
            } else {
                // Insert new user
                $insert_user = $conn->prepare("INSERT INTO users (id, name, email, password) VALUES (?, ?, ?, ?)");
                $insert_user->execute([$id, $name, $email, $hashedPass]);

                // Get the insertes user
                $select_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $select_user->execute([$email]);
                $row = $select_user->fetch(PDO::FETCH_ASSOC);

                // start session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];

                header('location: index.php');
                exit;
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
    <title>Gesge Restaurant - Register</title>
</head>
<body>
    <div class="main-container">
        <section class="form-container">
            <div class="title">
                <h1>register now</h1>
                <img src="images/separator.svg" alt="" class="separator">
                <p>you must register now, if you don't have account!</p>
            </div>

            <form action="" method="post">
                <div class="input-field">
                    <p>your name <span>*</span> </p>
                    <input type="text" name="name" required placeholder="enter your name" maxlength="50">
                </div>
                <div class="input-field">
                    <p>your email <span>*</span> </p>
                    <input type="email" name="email" required placeholder="enter your email" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
                <div class="input-field">
                    <p>your password <span>*</span> </p>
                    <input type="password" name="pass" required placeholder="enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
                <div class="input-field">
                    <p>confirm password <span>*</span> </p>
                    <input type="password" name="cpass" required placeholder="enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
                <input type="submit" name="submit" value="register now" class="btn">
                <p class="title-account">already have an account ? <a href="login.php">login now</a> </p>
            </form>
        </section>
    </div>
    
 
</body>

</html>