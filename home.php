<!DOCTYPE html>
<html>
<head>
<title>Supershop Home</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: url('img/bg.jpg') no-repeat center center fixed;
    background-size: cover;
}

.overlay {
    background: rgba(0,0,0,0.6);
    min-height: 100vh;
    color: white;
    padding-top: 80px;
}

.card {
    border-radius: 10px;
}
</style>
</head>

<body>

<div class="overlay">

<div class="container text-center">

<h1 class="mb-3"> Saudia Super Shop</h1>
<p class="mb-5">
Welcome to Saudia Super Shop — your trusted destination for fresh groceries and daily essentials. We provide quality products, fast checkout, and a smooth shopping experience to make your everyday shopping easy and convenient.
</p>

<div class="row justify-content-center">

<div class="col-md-3">
<div class="card p-3 shadow">
<h4>Shop</h4>
<p>Browse products & buy items</p>
<a href="index.php" class="btn btn-primary">Visit Shop</a>
</div>
</div>

<div class="col-md-3">
<div class="card p-3 shadow">
<h4> User</h4>
<p>Login or create account</p>
<a href="login.php" class="btn btn-success">User Login</a>
</div>
</div>

<div class="col-md-3">
<div class="card p-3 shadow">
<h4> Admin</h4>
<p>Manage system & data</p>
<a href="admin.php" class="btn btn-warning">Admin Panel</a>
</div>
</div>

</div>

<hr class="mt-5">

<h5> About Our Shop</h5>
<p>
We provide fresh daily groceries like rice, oil, milk, and eggs.
Our system ensures fast checkout and a smooth shopping experience.
Enjoy secure access and reliable service every day.
</p>

<p class="mt-3">
Location: Uttara, Sector 10, Road 12<br>
Contact: 01739990668
</p>

</div>

</div>

</body>
</html>