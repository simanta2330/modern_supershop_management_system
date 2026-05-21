<?php 
session_start();
include('db.php'); 


$search = $_GET['search'] ?? '';

$res = $conn->query("
    SELECT * FROM products
    WHERE product_name LIKE '%$search%'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supershop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body {
    background: url('img/bg.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.overlay {
    background: rgba(0, 0, 0, 0.6);
    min-height: 100vh;
    padding: 20px;
}

.card {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    transition: transform 0.3s ease;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 8px 20px;
}

.btn-primary:hover {
    transform: scale(1.05);
}

.btn-warning,
.btn-dark,
.btn-danger,
.btn-info,
.btn-success,
.btn-light {
    border-radius: 25px;
    margin: 3px;
}

.navbar-box {
    background: rgba(255,255,255,0.1);
    padding: 15px 20px;
    border-radius: 15px;
    backdrop-filter: blur(5px);
}

.shop-title {
    color: white;
    font-weight: bold;
}

.search-box{
    background: rgba(255,255,255,0.15);
    padding:15px;
    border-radius:15px;
    margin-bottom:20px;
    backdrop-filter: blur(5px);
}

.search-input{
    border-radius:25px !important;
    padding:12px;
}

</style>

</head>

<body>

<div class="overlay">

<div class="container">

    <div class="navbar-box d-flex justify-content-between align-items-center mb-4">

        <h3 class="shop-title m-0">
             Saudia Super Shop
        </h3>

        <div>

            <a href="home.php" class="btn btn-light btn-sm">
                 Home
            </a>

            <?php if(isset($_SESSION['user_id'])){ ?>

                <a href="orders.php" class="btn btn-info btn-sm">
                     My Orders
                </a>

                <a href="logout.php" class="btn btn-danger btn-sm">
                    Logout
                </a>

            <?php } else { ?>

                <a href="login.php" class="btn btn-dark btn-sm">
                     User Login
                </a>

            <?php } ?>

            <a href="admin.php" class="btn btn-warning btn-sm">
                 Admin Login
            </a>

            <a href="cart.php" class="btn btn-success btn-sm">
                 Cart
            </a>

        </div>

    </div>


    <div class="search-box">

        <form method="GET">

            <div class="row">

                <div class="col-md-10">

                    <input type="text"
                           name="search"
                           class="form-control search-input"
                           placeholder="🔍 Search products..."
                           value="<?= $search ?>">

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100 h-100">
                        Search
                    </button>

                </div>

            </div>

        </form>

    </div>

    <div class="row">

    <?php
    while($row = $res->fetch_assoc()){
    ?>

        <div class="col-md-3">

            <div class="card shadow mb-4">

                <img src="img/<?= $row['image'] ?>" 
                     class="card-img-top"
                     height="200"
                     style="object-fit: cover;">

                <div class="card-body text-center">

                    <h5>
                        <?= $row['product_name'] ?>
                    </h5>

                    <p class="text-success fw-bold fs-5">
                        <?= $row['selling_price'] ?> ৳
                    </p>

                    <p>
                        Stock: <?= $row['stock_qty'] ?>
                    </p>

                    <a href="add_to_cart.php?id=<?= $row['product_id'] ?>" 
                       class="btn btn-primary btn-sm">

                        Add to Cart

                    </a>

                </div>

            </div>

        </div>

    <?php } ?>

    </div>

</div>

</div>

</body>
</html>