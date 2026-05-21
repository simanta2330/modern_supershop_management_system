<?php
include('db.php');

if($_POST){
    $name     = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $pass     = $_POST['password'] ?? '';

    if($name && $username && $email && $pass){

        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

        $check = $conn->query("SELECT * FROM users WHERE username='$username'");
        if($check->num_rows > 0){
            $error = " Username already exists!";
        } else {

            $conn->query("INSERT INTO users(full_name, username, email, password_hash, role)
            VALUES('$name','$username','$email','$hashed_password','user')");

            $success = " Registration Successful!";
        }

    } else {
        $error = " All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>

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

.box {
    width: 350px;
    padding: 25px;
    background: white;
    border-radius: 10px;
}
</style>

</head>
<body>

<div class="overlay">
<div class="box">

<h3 class="text-center mb-3"> Register</h3>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<?php if(isset($success)){ ?>
<div class="alert alert-success"><?= $success ?></div>
<?php } ?>

<form method="post">

<input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>

<input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button class="btn btn-success w-100">Register</button>

</form>

<div class="text-center mt-3">
    <a href="login.php">Already have account? Login</a>
</div>

</div>
</div>

</body>
</html>

<div class="overlay">

<div class="box">

<h3 class="text-center mb-3"> Create Account</h3>

<?php if(isset($success)){ ?>
<div class="alert alert-success"><?= $success ?></div>
<?php } ?>

<form method="post">

<input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
<button class="btn btn-success w-100">Register</button>

</form>

<div class="text-center mt-3">
    <a href="login.php">Already have account? Login</a>
</div>

<div class="text-center mt-2">
    <a href="index.php">Back to Home</a>
</div>

</div>

</div>

</body>
</html>