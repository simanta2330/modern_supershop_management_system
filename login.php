<?php
session_start();
include('db.php');
if(isset($_SESSION['redirect'])){
    $redirect = $_SESSION['redirect'];
    unset($_SESSION['redirect']);
    header("Location: $redirect");
}
if($_POST){
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $res->fetch_assoc();

    if($user && password_verify($pass, $user['password_hash'])){
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role']    = $user['role'];

        if(isset($_SESSION['redirect'])){
            $redirect = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
            header("Location: $redirect");
        } else {
           
            if($user['role']=="admin"){
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
        }
        exit();

    } else {
        $error = " Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Supershop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .overlay {
            background: rgba(255,255,255,0.8);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 350px;
            padding: 25px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="overlay">

<div class="login-box">

<h3 class="text-center mb-3"> Supershop Login</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="post">

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button class="btn btn-primary w-100">Login</button>

</form>

<div class="text-center mt-3">
    <a href="register.php" class="btn btn-success w-100 mb-2">
         Create Account
    </a>

    <a href="home.php" class="btn btn-warning w-100">
         Back to Home
    </a>
</div>

</div>

</div>

</body>
</html>